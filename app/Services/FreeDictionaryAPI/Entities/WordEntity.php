<?php

namespace App\Services\FreeDictionaryApi\Entities;

class WordEntity implements ArrayableInterface
{
    public function __construct(
        private string $word,
        private array $phonetics,
        private array $meanings,
        private LicenseEntity $license,
        private array $sourceUrls
    ) {}

    public function toArray(): array
    {
        return [
            'word' => $this->word,
            'phonetics' => collect($this->phonetics)->map(fn($phonetic) => $phonetic->toArray())->toArray(),
            'meanings' => collect($this->meanings)->map(fn($meaning) => $meaning->toArray())->toArray(),
            'license' => $this->license->toArray(),
            'sourceUrls' => $this->sourceUrls,
        ];
    }

    /**
     * Gets the word.
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Gets the phonetics.
     *
     * @return array
     */
    public function getPhonetics()
    {
        return $this->phonetics;
    }

    /**
     * Gets the meanings.
     *
     * @return array
     */
    public function getMeanings()
    {
        return $this->meanings;
    }

    /**
     * Gets the license.
     *
     * @return LicenseEntity
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Gets the sourceUrls.
     *
     * @return array
     */
    public function getSourceUrls()
    {
        return $this->sourceUrls;
    }
}
