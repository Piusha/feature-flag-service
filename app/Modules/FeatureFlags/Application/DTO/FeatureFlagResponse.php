<?php

namespace App\Modules\FeatureFlags\Application\DTO;

final class FeatureFlagResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $key,
        public readonly string $name,
        public readonly ?string $description,
        public readonly string $type,
        public readonly string $scope,
        public readonly bool $enabled,
        public readonly ?int $rolloutPercentage,
        public readonly ?string $startsAt,
        public readonly ?string $expiresAt,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'scope' => $this->scope,
            'enabled' => $this->enabled,
            'rollout_percentage' => $this->rolloutPercentage,
            'starts_at' => $this->startsAt,
            'expires_at' => $this->expiresAt,
        ];
    }
}
