<?php

namespace App\Services\FreeDictionaryApi\Entities;


class LicenseEntity implements ArrayableInterface
{
    public function __construct(private string $name, private string $url) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url
        ];
    }
}
