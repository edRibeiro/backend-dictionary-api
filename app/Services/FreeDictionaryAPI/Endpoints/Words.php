<?php

namespace App\Services\FreeDictionaryApi\Endpoints;

use App\Services\FreeDictionaryApi\Builders\MeaningEntityBuilder;
use App\Services\FreeDictionaryAPI\Builders\PhoneticEntityBuilder;
use App\Services\FreeDictionaryAPI\Builders\WordEntityBuilder;
use App\Services\FreeDictionaryApi\Entities\DefinitionEntity;
use App\Services\FreeDictionaryApi\Entities\WordEntity;

class Words extends BaseEndpoint
{
    public function get(string $word): WordEntity | null
    {
        $response =  $this->service->api
            ->get("/$word");

        if ($response->failed()) return null;
        $body = $response->json();
        return (new WordEntityBuilder)
            ->setWord($body[0]['word'])
            ->setPhonetics(
                collect($body[0]['phonetics'])->map(
                    function ($phonetic) {
                        $phoneticBuilder = new PhoneticEntityBuilder();
                        $phoneticBuilder->setText($phonetic['text'] ?? null);
                        $phoneticBuilder->setAudio($phonetic['audio'] ?? null);
                        if (key_exists('license', $phonetic)) {
                            $phoneticBuilder->setLicense($phonetic['license']['name'], $phonetic['license']['url']);
                        }
                        $phoneticBuilder->setSourceUrl($phonetic['sourceUrl'] ?? null);
                        return $phoneticBuilder->build();
                    }
                )->toArray()
            )
            ->setMeanings(
                collect($body[0]['meanings'])
                    ->map(
                        function ($meaning) {
                            $meaningBuilder = new MeaningEntityBuilder();
                            $meaningBuilder->setPartOfSpeech($meaning['partOfSpeech']);
                            $meaningBuilder->setDefinitions(
                                collect($meaning['definitions'])->map(
                                    function ($definition) {
                                        return new DefinitionEntity(
                                            $definition['definition'],
                                            $definition['synonyms'],
                                            $definition['antonyms'],
                                            $definition['example'] ?? null
                                        );
                                    }
                                )->toArray()
                            );
                            $meaningBuilder->setSynonyms($meaning['synonyms']);
                            $meaningBuilder->setAntonyms($meaning['antonyms']);
                            return $meaningBuilder->build();
                        }
                    )->toArray()
            )
            ->setLicense($body[0]['license']['name'], $body[0]['license']['url'])
            ->setSourceUrls($body[0]['sourceUrls'])
            ->build();
    }
}
