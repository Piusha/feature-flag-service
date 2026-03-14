<?php

namespace App\Modules\FeatureFlags\Infrastructure\Mappers;

use App\Modules\FeatureFlags\Domain\Entities\FeatureFlagEntity;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Domain\ValueObjects\FlagSchedule;
use App\Modules\FeatureFlags\Domain\ValueObjects\RolloutPercentage;
use App\Modules\FeatureFlags\Infrastructure\Models\FeatureFlag;

final class FeatureFlagMapper
{
    public function toDomain(FeatureFlag $model): FeatureFlagEntity
    {
        return new FeatureFlagEntity(
            id: $model->id,
            key: new FeatureFlagKey($model->key),
            name: $model->name,
            description: $model->description,
            type: $model->type,
            scope: $model->scope,
            enabled: (bool) $model->enabled,
            rolloutPercentage: $model->rollout_percentage !== null
                ? new RolloutPercentage((int) $model->rollout_percentage)
                : null,
            schedule: new FlagSchedule(
                startsAt: $model->starts_at?->toDateTimeImmutable(),
                expiresAt: $model->expires_at?->toDateTimeImmutable(),
            ),
        );
    }

    public function toPersistence(FeatureFlagEntity $entity): array
    {
        return [
            'key' => $entity->key()->value(),
            'name' => $entity->name(),
            'description' => $entity->description(),
            'type' => $entity->type()->value,
            'scope' => $entity->scope()->value,
            'enabled' => $entity->enabled(),
            'rollout_percentage' => $entity->rolloutPercentage()?->value(),
            'starts_at' => $entity->schedule()->startsAt()?->format('Y-m-d H:i:s'),
            'expires_at' => $entity->schedule()->expiresAt()?->format('Y-m-d H:i:s'),
        ];
    }
}
