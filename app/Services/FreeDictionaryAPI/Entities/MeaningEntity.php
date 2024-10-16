<?php

namespace App\Services\FreeDictionaryApi\Entities;


class MeaningEntity implements ArrayableInterface
{

    public function __construct(
        private string $partOfSpeech,
        private array $definitions,
        private array $synonyms,
        private array $antonyms
    ) {}

    public function toArray(): array
    {
        return [
            'partOfSpeech' => $this->partOfSpeech,
            'definitions' => array_map(fn($definition) => $definition->toArray(), $this->definitions),
            'synonyms' => $this->synonyms,
            'antonyms' => $this->antonyms,
        ];
    }

    /**
     * Gets the partOfSpeech.
     *
     * @return string
     */
    public function getPartOfSpeech(): string
    {
        return $this->partOfSpeech;
    }

    /**
     * Gets the definitions.
     *
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * Gets the synonyms.
     *
     * @return array
     */
    public function getSynonyms(): array
    {
        return $this->synonyms;
    }

    /**
     * Gets the antonyms.
     *
     * @return array
     */
    public function getAntonyms(): array
    {
        return $this->antonyms;
    }
}
