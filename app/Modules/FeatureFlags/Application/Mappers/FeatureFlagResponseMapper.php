<?php

namespace App\Modules\FeatureFlags\Application\Mappers;

use App\Modules\FeatureFlags\Application\DTO\FeatureFlagResponse;
use App\Modules\FeatureFlags\Domain\Entities\FeatureFlagEntity;

final class FeatureFlagResponseMapper
{
    public function toResponse(FeatureFlagEntity $entity): FeatureFlagResponse
    {
        return new FeatureFlagResponse(
            id: $entity->id() ?? 0,
            key: $entity->key()->value(),
            name: $entity->name(),
            description: $entity->description(),
            type: $entity->type()->value,
            scope: $entity->scope()->value,
            enabled: $entity->enabled(),
            rolloutPercentage: $entity->rolloutPercentage()?->value(),
            startsAt: $entity->schedule()->startsAt()?->format(DATE_ATOM),
            expiresAt: $entity->schedule()->expiresAt()?->format(DATE_ATOM),
        );
    }
}
