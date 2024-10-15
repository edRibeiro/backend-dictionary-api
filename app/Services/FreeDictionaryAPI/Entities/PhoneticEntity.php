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
}
