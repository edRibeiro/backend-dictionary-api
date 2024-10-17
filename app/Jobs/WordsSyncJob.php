<?php

namespace App\Jobs;

use App\Models\Word;
use App\Services\FreeDictionaryApi\Facades\FreeDictionaryApi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Arr;
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
        $wordEntity = FreeDictionaryApi::words()->get($this->word);
        $wordModelExistes = Word::where('word', '=', $this->word)->first();
        if ($wordEntity && !$wordModelExistes) {
            $wordModel = Word::create(['word' => $wordEntity->getWord(), 'license' => $wordEntity->getLicense()->getName(), 'license_url' => $wordEntity->getLicense()->getUrl()]);
            foreach ($wordEntity->getPhonetics() as $key => $phonetic) {
                $wordModel->phonetics()->create(['text' => $phonetic->getText(), 'audio' => $phonetic->getAudio(), 'source_url' => $phonetic->getSourceUrl(), 'license' => $phonetic->getLicense() ? $phonetic->getLicense()->getUrl() : null]);
            }
            foreach ($wordEntity->getMeanings() as $key => $meaning) {
                $meaningModel =  $wordModel->meanings()->create(['part_of_speech' => $meaning->getPartOfSpeech()]);
                foreach ($meaning->getDefinitions() as $key => $definition) {
                    $definitionData = $definition->toArray();
                    $definition = $meaningModel->definitions()->create(Arr::only($definitionData, ['definition', 'example']));
                    $definition->synonyms()->createMany(
                        Arr::map($definitionData['synonyms'], function (string $item, int $key) {
                            return ['word' => $item];
                        })
                    );
                    $definition->antonyms()->createMany(
                        Arr::map($definitionData['antonyms'], function (string $item, int $key) {
                            return ['word' => $item];
                        })
                    );
                }
                $meaningModel->synonyms()->createMany(Arr::map($meaning->getSynonyms(), function (string $item, int $key) {
                    return ['word' => $item];
                }));
                $meaningModel->antonyms()->createMany(Arr::map($meaning->getAntonyms(), function (string $item, int $key) {
                    return ['word' => $item];
                }));
            }
        } else {
            Redis::sadd('no_found_word', $this->word);
        }
    }
}
