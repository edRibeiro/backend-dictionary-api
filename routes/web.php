<?php

// use App\Models\Meaning;
// use App\Models\Word;
// use App\Services\FreeDictionaryApi\Facades\FreeDictionaryApi;
use Illuminate\Http\Response;
// use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(["message" => "Fullstack Challenge 🏅 - Dictionary"], Response::HTTP_OK, ["content-type" => "application/json"]);
});

/* Route::get('/{word}', function ($word) {
    $wordEntity = FreeDictionaryApi::words()->get($word);
    $wordModel = Word::create(['word' => $wordEntity->getWord(), 'license' => $wordEntity->getLicense()->getName(), 'license_url' => $wordEntity->getLicense()->getUrl()]);
    foreach ($wordEntity->getPhonetics() as $key => $phonetic) {
        $wordModel->phonetics()->create(['text' => $phonetic->getText(), 'audio' => $phonetic->getAudio(), 'source_url' => $phonetic->getSourceUrl(), 'license' => $phonetic->getLicense()->getUrl()]);
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
    dd($wordEntity, $wordModel->load(['phonetics', 'meanings.definitions', 'meanings.definitions.synonyms', 'meanings.synonyms', 'meanings.antonyms', 'meanings.definitions.antonyms']));
    return response();
}); */
