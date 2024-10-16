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

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
