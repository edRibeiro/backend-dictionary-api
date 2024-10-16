<?php

namespace App\Services\FreeDictionaryApi\Entities;

class PhoneticEntity implements ArrayableInterface
{
    public function __construct(
        private ?string $text = null,
        private ?string $audio = null,
        private ?LicenseEntity $license = null,
        private ?string $sourceUrl = null
    ) {}

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'audio' => $this->audio,
            'sourceUrl' => $this->sourceUrl,
            'license' => !empty($this->license) ? $this->license->toArray() : null
        ];
    }

    /**
     * Gets the text.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Gets the audio.
     *
     * @return string|null
     */
    public function getAudio(): ?string
    {
        return $this->audio;
    }

    /**
     * Gets the license.
     *
     * @return LicenseEntity|null
     */
    public function getLicense(): ?LicenseEntity
    {
        return $this->license;
    }

    /**
     * Gets the sourceUrl.
     *
     * @return string|null
     */
    public function getSourceUrl(): ?string
    {
        return $this->sourceUrl;
    }
}
