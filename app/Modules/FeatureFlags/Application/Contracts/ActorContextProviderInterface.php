<?php

namespace App\Modules\FeatureFlags\Application\Contracts;

use App\Modules\FeatureFlags\Application\DTO\ActorContextData;

interface ActorContextProviderInterface
{
    public function resolve(): ActorContextData;
}
