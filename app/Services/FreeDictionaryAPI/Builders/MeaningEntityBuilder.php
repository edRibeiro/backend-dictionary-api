<?php

namespace App\Services\FreeDictionaryApi\Builders;

use App\Services\FreeDictionaryApi\Entities\MeaningEntity;
use App\Services\FreeDictionaryApi\Entities\DefinitionEntity;

class MeaningEntityBuilder
{
    private string $partOfSpeech;
    private array $definitions = [];
    private array $synonyms = [];
    private array $antonyms = [];

    public function setPartOfSpeech(string $partOfSpeech): self
    {
        $this->partOfSpeech = $partOfSpeech;
        return $this;
    }

    public function addDefinition(DefinitionEntity $definition): self
    {
        $this->definitions[] = $definition;
        return $this;
    }

    public function setDefinitions(array $definitions): self
    {
        $this->definitions = $definitions;
        return $this;
    }

    public function setSynonyms(array $synonyms): self
    {
        $this->synonyms = $synonyms;
        return $this;
    }

    public function setAntonyms(array $antonyms): self
    {
        $this->antonyms = $antonyms;
        return $this;
    }

    public function build(): MeaningEntity
    {
        return new MeaningEntity(
            $this->partOfSpeech,
            $this->definitions,
            $this->synonyms,
            $this->antonyms
        );
    }
}
