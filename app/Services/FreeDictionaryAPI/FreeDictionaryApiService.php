<?php

namespace App\Services\FreeDictionaryApi;

use App\Services\FreeDictionaryApi\Endpoints\HasWords;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class FreeDictionaryApiService
{
    use HasWords;

    public PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::withHeaders([
            'Accept'  => "application/json",

        ])->baseUrl('https://api.dictionaryapi.dev/api/v2/entries/en');
    }
}
