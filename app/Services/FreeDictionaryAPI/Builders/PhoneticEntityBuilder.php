<?php

namespace App\Services\FreeDictionaryAPI\Builders;

use App\Services\FreeDictionaryApi\Entities\LicenseEntity;
use App\Services\FreeDictionaryApi\Entities\PhoneticEntity;

class PhoneticEntityBuilder implements BuilderInterface
{
    private ?string $text = null;
    private ?string $audio = null;
    private ?LicenseEntity $license = null;
    private ?string $sourceUrl = null;

    public function setText(?string $text = null): self
    {
        $this->text = $text;
        return $this;
    }

    public function setAudio(?string $audio = null): self
    {
        $this->audio = $audio;
        return $this;
    }

    public function setLicense(string $name,  string $url): self
    {
        $this->license = new LicenseEntity($name, $url);
        return $this;
    }

    public function setSourceUrl(?string $sourceUrl): self
    {
        $this->sourceUrl = $sourceUrl;
        return $this;
    }

    public function build(): PhoneticEntity
    {
        return new PhoneticEntity(
            $this->text,
            $this->audio,
            $this->license,
            $this->sourceUrl
        );
    }
}
