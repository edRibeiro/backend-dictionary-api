<?php

namespace App\Services\FreeDictionaryAPI\Builders;

use App\Services\FreeDictionaryApi\Entities\LicenseEntity;
use App\Services\FreeDictionaryApi\Entities\WordEntity;

class WordEntityBuilder implements BuilderInterface
{
    private string $word;
    private array $phonetics = [];
    private array $meanings = [];
    private LicenseEntity $license;
    private array $sourceUrls = [];


    public function setWord(string $word): self
    {
        $this->word = $word;
        return $this;
    }

    public function setPhonetics(array $phonetics): self
    {
        $this->phonetics = $phonetics;
        return $this;
    }

    public function setMeanings(array $meanings): self
    {
        $this->meanings = $meanings;
        return $this;
    }

    public function setLicense(string $name,  string $url): self
    {
        $this->license = new LicenseEntity($name, $url);
        return $this;
    }

    public function setSourceUrls(array $sourceUrls): self
    {
        $this->sourceUrls = $sourceUrls;
        return $this;
    }

    public function build(): WordEntity
    {
        return
            new WordEntity(
                $this->word,
                $this->phonetics,
                $this->meanings,
                $this->license,
                $this->sourceUrls
            );
    }
}
