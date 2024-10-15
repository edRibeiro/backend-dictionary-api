<?php

namespace App\Services\FreeDictionaryApi\Entities;


class MeaningEntity implements ArrayableInterface
{

    public function __construct(private string $partOfSpeech, private array $definitions, private array $synonyms, private array $antonyms) {}

    public function toArray(): array
    {
        return [
            'partOfSpeech' => $this->partOfSpeech,
            'definitions' => array_map(fn($definition) => $definition->toArray(), $this->definitions),
            'synonyms' => $this->synonyms,
            'antonyms' => $this->antonyms,
        ];
    }
}
