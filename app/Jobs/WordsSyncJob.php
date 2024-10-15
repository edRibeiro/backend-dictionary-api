<?php

namespace App\Jobs;

use App\Services\FreeDictionaryApi\Facades\FreeDictionaryApi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Redis;

class WordsSyncJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $word)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $word = FreeDictionaryApi::words()->get($this->word);
        if ($word) {
            // @TODO pressitir no banco de dados
            logger("Palavrea encontrada: $this->word.");
        } else {
            $exists = Redis::sadd('no_found_word', $this->word);
            logger()->error("no_found_word:$this->word.");
        }
    }
}
