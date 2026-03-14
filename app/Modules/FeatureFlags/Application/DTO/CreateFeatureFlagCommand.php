<?php

namespace App\Modules\FeatureFlags\Application\DTO;

use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagScope;
use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagType;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Domain\ValueObjects\FlagSchedule;
use App\Modules\FeatureFlags\Domain\ValueObjects\RolloutPercentage;

final class CreateFeatureFlagCommand
{
    public function __construct(
        public readonly FeatureFlagKey $key,
        public readonly string $name,
        public readonly ?string $description,
        public readonly FeatureFlagType $type,
        public readonly FeatureFlagScope $scope,
        public readonly bool $enabled,
        public readonly ?RolloutPercentage $rolloutPercentage,
        public readonly FlagSchedule $schedule,
    ) {}
}
