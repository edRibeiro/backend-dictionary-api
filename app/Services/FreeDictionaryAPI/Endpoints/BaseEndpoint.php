<?php

namespace App\Services\FreeDictionaryApi\Endpoints;

use App\Services\FreeDictionaryApi\FreeDictionaryApiService;

class BaseEndpoint
{
    protected FreeDictionaryApiService $service;

    public function __construct()
    {
        $this->service = new FreeDictionaryApiService();
    }
}
