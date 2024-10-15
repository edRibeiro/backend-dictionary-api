<?php

namespace App\Services\FreeDictionaryApi\Endpoints;

trait HasWords
{
    public function words(): Words
    {
        return new Words();
    }
}
