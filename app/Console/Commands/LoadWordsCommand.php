<?php

namespace App\Console\Commands;

use App\Jobs\FetchWordsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\LazyCollection;

class LoadWordsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carrega as palavras de um arquivo JSON remoto.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://raw.githubusercontent.com/dwyl/english-words/refs/heads/master/words_dictionary.json';
        $response = Http::get($url);
        if ($response->successful()) {
            $body = $response->json();
            $source = array_chunk($body, 100);
            $bar = $this->output->createProgressBar(count($source));
            $bar->start();
            foreach ($source as $key => $words) {
                FetchWordsJob::dispatch($words);
                $bar->advance();
            }
            $bar->finish();
        } else {
            $this->error('Falha ao carregar o JSON da URL: ' . $url);
        }

        return 0;
    }
}
