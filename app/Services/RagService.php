<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RagService
{
    protected $pinecone;
    protected $ollama;
    protected int $connectTimeout;
    protected int $requestTimeout;
    protected int $retries;
    protected int $retryDelayMs;

    public function __construct()
    {
        // Keep these comfortably below PHP max_execution_time to avoid fatal timeouts.
        $this->connectTimeout = (int) env('RAG_CONNECT_TIMEOUT', 3);
        $this->requestTimeout = (int) env('RAG_REQUEST_TIMEOUT', 8);
        $this->retries = (int) env('RAG_HTTP_RETRIES', 1);
        $this->retryDelayMs = (int) env('RAG_HTTP_RETRY_DELAY_MS', 200);

        $this->pinecone = new Client([
            'base_uri' => env('PINECONE_URL'),
            'headers' => [
                'Api-Key' => env('PINECONE_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'connect_timeout' => $this->connectTimeout,
            'timeout' => $this->requestTimeout,
            'http_errors' => false,
        ]);

        $this->ollama = new Client([
            'base_uri' => 'http://localhost:11434/',
            'connect_timeout' => $this->connectTimeout,
            'timeout' => (int) env('RAG_OLLAMA_TIMEOUT', 20),
            'http_errors' => false,
        ]);
    }

    private function postJsonWithRetry(Client $client, string $uri, array $json, string $label): array
    {
        $attempt = 0;
        $lastError = null;

        while ($attempt <= $this->retries) {
            $attempt++;
            try {
                $res = $client->post($uri, [
                    'json' => $json,
                    'connect_timeout' => $this->connectTimeout,
                    'timeout' => $this->requestTimeout,
                ]);

                $status = $res->getStatusCode();
                $bodyRaw = (string) $res->getBody();
                $body = json_decode($bodyRaw, true);

                if ($status >= 200 && $status < 300 && is_array($body)) {
                    return $body;
                }

                $lastError = new \RuntimeException("HTTP {$status} or invalid JSON for {$label}");
                Log::warning("{$label} non-2xx/invalid JSON", [
                    'status' => $status,
                    'attempt' => $attempt,
                    'body_prefix' => substr($bodyRaw, 0, 300),
                ]);
            } catch (GuzzleException|\Throwable $e) {
                $lastError = $e;
                Log::warning("{$label} request failed", [
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                ]);
            }

            if ($attempt <= $this->retries) {
                usleep($this->retryDelayMs * 1000);
            }
        }

        throw $lastError ?? new \RuntimeException("{$label} failed");
    }

    /**
     * Split a large text into smaller chunks
     */
    private function chunkText(string $text, int $chunkSize = 500): array
    {
        $words = preg_split('/\s+/', $text);
        $chunks = [];
        $current = [];

        foreach ($words as $word) {
            $current[] = $word;
            if (count($current) >= $chunkSize) {
                $chunks[] = implode(' ', $current);
                $current = [];
            }
        }
        if (!empty($current)) {
            $chunks[] = implode(' ', $current);
        }

        return $chunks;
    }

    /**
     * Add a document to Pinecone with embedding and topic metadata
     * FIX 1: Ensure the 'topics' metadata is stored correctly as an array of strings.
     */
    public function addDocument(string $id, string $text, array $topics = [])
    {
        // Pinecone metadata arrays must be simple, flat arrays of strings/numbers
        // Ensure all topics are lowercased strings
        $topics = array_map('strtolower', $topics);

        $chunks = $this->chunkText($text, 500);
        $batchSize = 10;
        $totalChunks = count($chunks);

        for ($i = 0; $i < $totalChunks; $i += $batchSize) {
            $batchChunks = array_slice($chunks, $i, $batchSize);

            $embeddings = [];
            foreach ($batchChunks as $chunk) {
                try {
                    $body = $this->postJsonWithRetry($this->ollama, "api/embeddings", [
                        'model' => 'nomic-embed-text:latest',
                        'prompt' => $chunk,
                    ], 'Ollama embeddings (document chunk)');

                    $embeddings[] = $body['embedding'] ?? null;
                } catch (\Throwable $e) {
                    Log::error("Failed to generate embedding: " . $e->getMessage());
                    $embeddings[] = null;
                }
            }

            // Prepare Pinecone batch
            $vectors = [];
            foreach ($embeddings as $j => $embedding) {
                if ($embedding === null) continue;
                $vectors[] = [
                    'id' => "{$id}_chunk" . ($i + $j),
                    'values' => $embedding,
                    'metadata' => [
                        'text' => $batchChunks[$j],
                        'doc_id' => $id,
                        'chunk' => $i + $j,
                        'topics' => $topics // This is now a simple array of strings
                    ]
                ];
            }

            // Upsert batch
            if (!empty($vectors)) {
                try {
                    $this->postJsonWithRetry($this->pinecone, "vectors/upsert", ['vectors' => $vectors], 'Pinecone upsert');
                } catch (\Throwable $e) {
                    Log::error("Failed to upsert batch to Pinecone: " . $e->getMessage());
                }
            }

            Log::info("Processed batch " . ($i / $batchSize + 1) . " of document {$id}");
        }
    }

    /**
     * Retrieve top-K relevant chunks from Pinecone filtered by allowed topics
     */
    public function retrieve(string $query, int $topK = 10, array $allowedTopics = []): array
    {
        $startedAt = microtime(true);
        $timeBudgetSec = (float) env('RAG_RETRIEVE_BUDGET_SEC', 10);

        try {
            $cacheTtlSec = (int) env('RAG_CACHE_TTL_SEC', 300);
            $queryKey = 'rag:q:' . sha1(mb_strtolower(trim($query)));
            $allowedTopicsKey = !empty($allowedTopics) ? sha1(implode('|', array_map('strtolower', $allowedTopics))) : 'all';

            // Generate embedding for query (cached)
            $embedding = Cache::remember($queryKey . ':emb', $cacheTtlSec, function () use ($query) {
                $body = $this->postJsonWithRetry($this->ollama, "api/embeddings", [
                    'model' => 'nomic-embed-text:latest',
                    'prompt' => $query,
                ], 'Ollama embeddings (query)');

                return $body['embedding'] ?? null;
            });

            if (empty($embedding) || !is_array($embedding)) {
                return [];
            }

            $vector = $embedding;

            // Prepare Pinecone filter
            $filter = [];
            if (!empty($allowedTopics)) {
                // Ensure allowed topics are lowercased for filtering consistency
                $allowedTopics = array_map('strtolower', $allowedTopics);
                
                // This is the correct filter logic for an array field in Pinecone:
                // Find vectors where the 'topics' array field CONTAINS ANY of the $allowedTopics.
                $filter = [
                    'topics' => [
                        '$in' => $allowedTopics
                    ]
                ];
            }

            if ((microtime(true) - $startedAt) > $timeBudgetSec) {
                Log::warning('RAG retrieve exceeded budget before Pinecone query', [
                    'budget_sec' => $timeBudgetSec,
                    'elapsed_sec' => microtime(true) - $startedAt,
                ]);
                return [];
            }

            // Query Pinecone (cached per query+topics+topK)
            $pineconeKey = $queryKey . ':pc:' . $allowedTopicsKey . ':k' . $topK;
            $qBody = Cache::remember($pineconeKey, $cacheTtlSec, function () use ($vector, $topK, $filter) {
                return $this->postJsonWithRetry($this->pinecone, "query", [
                    'vector' => $vector,
                    'topK' => $topK,
                    'includeMetadata' => true,
                    'filter' => $filter,
                ], 'Pinecone query');
            });

            // Handling the different Pinecone response structures (collections vs indexes)
            $matches = $qBody['matches'] ?? $qBody['results'][0]['matches'] ?? []; 

            // FIX 2: Confirm low similarity threshold (this is already correctly at 0.4)
            $threshold = 0.4;
            $texts = [];
            foreach ($matches as $m) {
                $score = $m['score'] ?? 0;
                if ($score >= $threshold && isset($m['metadata']['text'])) {
                    $texts[] = $m['metadata']['text'];
                }
            }
            
            // Log for debugging:
            Log::info('RAG Retrieved Matches Count: ' . count($texts) . ' (Threshold: ' . $threshold . ')');
            
            return $texts;
        } catch (\Throwable $e) {
            Log::error("Retrieve failed: " . $e->getMessage());
            return [];
        } finally {
            $elapsed = microtime(true) - $startedAt;
            if ($elapsed > 2.0) {
                Log::info('RAG retrieve timing', ['elapsed_sec' => round($elapsed, 3)]);
            }
        }
    }
}