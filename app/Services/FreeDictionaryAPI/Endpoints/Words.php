<?php

namespace App\Services\FreeDictionaryApi\Endpoints;

use App\Services\FreeDictionaryApi\Entities\WordEntity;

class Words extends BaseEndpoint
{
    public function get(string $word): WordEntity
    {
        return $this->service->api
            ->get("/$word")
            ->json();
    }
}
