<?php

namespace App\Services\FreeDictionaryApi\Entities;

class DefinitionEntity implements ArrayableInterface
{
    public function __construct(
        private string $definition,
        private array $synonyms = [],
        private array $antonyms = [],
        private ?string $example = null
    ) {}

    public function toArray(): array
    {
        return [
            'definition' => $this->definition,
            'synonyms' => $this->synonyms,
            'antonyms' => $this->antonyms,
            'example' => $this->example,
        ];
    }
}
