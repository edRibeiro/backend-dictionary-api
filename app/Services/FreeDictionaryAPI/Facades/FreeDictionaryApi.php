<?php

namespace App\Services\FreeDictionaryApi\Facades;

use App\Services\FreeDictionaryApi\Endpoints\Words;
use App\Services\FreeDictionaryApi\FreeDictionaryApiService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Words words()
 */
class FreeDictionaryApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FreeDictionaryApiService::class;
    }
}
