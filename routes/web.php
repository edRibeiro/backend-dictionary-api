<?php

use App\Services\FreeDictionaryApi\Facades\FreeDictionaryApi;
use Illuminate\Support\Facades\Route;

Route::get('/{word}', function ($word) {
    return json_encode(FreeDictionaryApi::words()->get($word)->toArray());
});
