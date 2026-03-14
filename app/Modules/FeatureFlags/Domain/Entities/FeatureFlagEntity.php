<?php

namespace App\Modules\FeatureFlags\Domain\Entities;

use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagScope;
use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagType;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Domain\ValueObjects\FlagSchedule;
use App\Modules\FeatureFlags\Domain\ValueObjects\RolloutPercentage;

final class FeatureFlagEntity
{
    public function __construct(
        private readonly ?int $id,
        private readonly FeatureFlagKey $key,
        private readonly string $name,
        private readonly ?string $description,
        private readonly FeatureFlagType $type,
        private readonly FeatureFlagScope $scope,
        private readonly bool $enabled,
        private readonly ?RolloutPercentage $rolloutPercentage,
        private readonly FlagSchedule $schedule,
    ) {}

    public function id(): ?int
    {
        return $this->id;
    }
    public function key(): FeatureFlagKey
    {
        return $this->key;
    }
    public function name(): string
    {
        return $this->name;
    }
    public function description(): ?string
    {
        return $this->description;
    }
    public function type(): FeatureFlagType
    {
        return $this->type;
    }
    public function scope(): FeatureFlagScope
    {
        return $this->scope;
    }
    public function enabled(): bool
    {
        return $this->enabled;
    }
    public function rolloutPercentage(): ?RolloutPercentage
    {
        return $this->rolloutPercentage;
    }
    public function schedule(): FlagSchedule
    {
        return $this->schedule;
    }

    public function withId(int $id): self
    {
        return new self(
            id: $id,
            key: $this->key,
            name: $this->name,
            description: $this->description,
            type: $this->type,
            scope: $this->scope,
            enabled: $this->enabled,
            rolloutPercentage: $this->rolloutPercentage,
            schedule: $this->schedule,
        );
    }
}
