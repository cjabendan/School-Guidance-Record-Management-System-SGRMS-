<?php

namespace App\Services;

use GuzzleHttp\Client;

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
            ]
        ]);

        $this->ollama = new Client([
            'base_uri' => 'http://localhost:11434/'
        ]);
    }

    private function chunkText(string $text, int $chunkSize = 500): array
    {
        $words  = preg_split('/\s+/', $text);
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

    public function addDocument(string $id, string $text)
    {
        $chunks = $this->chunkText($text, 500);

        foreach ($chunks as $i => $chunk) {
            // 1. Generate embedding from Ollama
            $embeddingRes = $this->ollama->post("api/embeddings", [
                'json' => [
                    'model' => 'nomic-embed-text:latest',
                    'prompt' => $chunk
                ]
            ]);
            $embedding = json_decode($embeddingRes->getBody(), true)['embedding'];

            // 2. Store in Pinecone
            $this->pinecone->post("vectors/upsert", [
                'json' => [
                    'vectors' => [[
                        'id' => "{$id}_chunk{$i}",
                        'values' => $embedding,
                        'metadata' => [
                            'text' => $chunk,
                            'doc_id' => $id,
                            'chunk' => $i
                        ]
                    ]]
                ]
            ]);
        }
    }
}
