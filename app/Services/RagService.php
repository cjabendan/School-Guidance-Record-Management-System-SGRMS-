<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class RagService
{
    protected $pinecone;
    protected $ollama;

    public function __construct()
    {
        $this->pinecone = new Client([
            'base_uri' => env('PINECONE_URL'),
            'headers' => [
                'Api-Key' => env('PINECONE_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'timeout' => 60, // allow longer for large batches
        ]);

        $this->ollama = new Client([
            'base_uri' => 'http://localhost:11434/',
            'timeout' => 60,
        ]);
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
     */
    public function addDocument(string $id, string $text, array $topics = [])
    {
        $chunks = $this->chunkText($text, 500);
        $batchSize = 10;
        $totalChunks = count($chunks);

        for ($i = 0; $i < $totalChunks; $i += $batchSize) {
            $batchChunks = array_slice($chunks, $i, $batchSize);

            $embeddings = [];
            foreach ($batchChunks as $chunk) {
                try {
                    $res = $this->ollama->post("api/embeddings", [
                        'json' => [
                            'model' => 'nomic-embed-text:latest',
                            'prompt' => $chunk
                        ]
                    ]);
                    $body = json_decode($res->getBody(), true);
                    $embeddings[] = $body['embedding'] ?? null;
                } catch (\Exception $e) {
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
                        'text'   => $batchChunks[$j],
                        'doc_id' => $id,
                        'chunk'  => $i + $j,
                        'topics' => $topics // topic metadata for semantic filtering
                    ]
                ];
            }

            // Upsert batch
            if (!empty($vectors)) {
                try {
                    $this->pinecone->post("vectors/upsert", ['json' => ['vectors' => $vectors]]);
                } catch (\Exception $e) {
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
        try {
            // Generate embedding for query
            $embeddingRes = $this->ollama->post("api/embeddings", [
                'json' => [
                    'model' => 'nomic-embed-text:latest',
                    'prompt' => $query
                ]
            ]);

            $body = json_decode($embeddingRes->getBody(), true);
            if (empty($body['embedding'])) return [];

            $vector = $body['embedding'];

            // Prepare Pinecone filter
            $filter = [];
            if (!empty($allowedTopics)) {
                $filter['topics'] = $allowedTopics;
            }

            // Query Pinecone
            $queryRes = $this->pinecone->post("query", [
                'json' => [
                    'vector' => $vector,
                    'topK' => $topK,
                    'includeMetadata' => true,
                    'filter' => $filter
                ]
            ]);

            $qBody = json_decode($queryRes->getBody(), true);
            $matches = $qBody['matches'] ?? $qBody['results'][0]['matches'] ?? [];

            // Optional similarity threshold
            $threshold = 0.7;
            $texts = [];
            foreach ($matches as $m) {
                $score = $m['score'] ?? 0;
                if ($score >= $threshold && isset($m['metadata']['text'])) {
                    $texts[] = $m['metadata']['text'];
                }
            }

            return $texts;

        } catch (\Exception $e) {
            Log::error("Retrieve failed: " . $e->getMessage());
            return [];
        }
    }
}
