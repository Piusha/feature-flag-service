<?php

namespace App\Modules\FeatureFlags\Application\DTO;

use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagScope;
use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagType;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Domain\ValueObjects\RolloutPercentage;

final class UpdateFeatureFlagCommand
{
    public function __construct(
        public readonly int $id,
        public readonly FeatureFlagKey $key,
        public readonly string $name,
        public readonly ?string $description,
        public readonly bool $hasDescription,
        public readonly FeatureFlagType $type,
        public readonly FeatureFlagScope $scope,
        public readonly bool $enabled,
        public readonly ?RolloutPercentage $rolloutPercentage,
        public readonly bool $hasRolloutPercentage,
        public readonly ?\DateTimeImmutable $startsAt,
        public readonly bool $hasStartsAt,
        public readonly ?\DateTimeImmutable $expiresAt,
        public readonly bool $hasExpiresAt,
    ) {}
}
