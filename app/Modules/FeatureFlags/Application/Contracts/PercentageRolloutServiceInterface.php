<?php

namespace App\Modules\FeatureFlags\Application\Contracts;

use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Domain\ValueObjects\RolloutPercentage;

interface PercentageRolloutServiceInterface
{
    public function isInRollout(FeatureFlagKey $flagKey, ?RolloutPercentage $percentage): bool;

    public function bucketForFlagKey(FeatureFlagKey $flagKey): int;
}
