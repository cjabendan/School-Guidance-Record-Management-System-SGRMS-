<?php

namespace App\Jobs;

use App\Services\RagService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $text;

    public function __construct(string $id, string $text)
    {
        $this->id   = $id;
        $this->text = $text;
    }

    /**
     * Execute the job.
     * This is where policy topics are defined and passed for indexing.
     */
    public function handle(RagService $rag)
    {
        // Define a comprehensive list of tags for the entire school policy document.
        $topics = [
            'policy', 
            'discipline', 
            'school rules',
            'guidance', 
            'counseling', 
            'attendance', 
            'uniform', 
            'dress code', 
            'behavior', 
            'bullying', 
            'assault',
            'fighting',
            'cheating',
            'academic dishonesty',
            'vandalism',
            'technology',
            'cyberbullying',
            'suspension', 
            'expulsion',
            'safety',
            'health',
            'legal',
            'authority' // Important for queries like 'disrespect to authority'
        ];
        
        // Pass the topics array to the RAG service to be stored as vector metadata
        $rag->addDocument($this->id, $this->text, $topics);
    }
}