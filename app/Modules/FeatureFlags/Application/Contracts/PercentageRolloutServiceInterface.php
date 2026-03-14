<?php

namespace App\Modules\FeatureFlags\Application\Contracts;

use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Domain\ValueObjects\RolloutPercentage;

interface PercentageRolloutServiceInterface
{
    public function isUserInRollout(FeatureFlagKey $flagKey, string $userId, ?RolloutPercentage $percentage): bool;

    public function bucketForUser(FeatureFlagKey $flagKey, string $userId): int;
}
