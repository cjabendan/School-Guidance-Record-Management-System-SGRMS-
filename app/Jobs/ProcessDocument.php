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

    /**
     * Create a new job instance.
     */
    public function __construct(string $id, string $text)
    {
        $this->id   = $id;
        $this->text = $text;
    }

    /**
     * Execute the job.
     */
    public function handle(RagService $rag)
    {
        $rag->addDocument($this->id, $this->text);
    }
}
