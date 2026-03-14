<?php

namespace App\Modules\FeatureFlags\Application\DTO;

final class ActorContextData
{
    public function __construct(
        public readonly ?string $actorId,
        public readonly ?string $actorType,
    ) {}
}
