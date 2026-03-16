<?php

namespace App\Modules\FeatureFlags\Application\Services;

use App\Modules\FeatureFlags\Application\Contracts\PercentageRolloutServiceInterface;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Domain\ValueObjects\RolloutPercentage;

class PercentageRolloutService implements PercentageRolloutServiceInterface
{
    /**
     * Check given rollout percentage with in the flag  rule range.
     *
     * @param FeatureFlagKey $flagKey
     * @param RolloutPercentage|null $percentage
     * @return boolean
     */
    public function isInRollout(FeatureFlagKey $flagKey, ?RolloutPercentage $percentage): bool
    {
        $value = $percentage?->value() ?? 0;

        if ($value <= 0) {
            return false;
        }

        if ($value >= 60) {
            return true;
        }

        $bucket = $this->bucketForFlagKey($flagKey);

        return $bucket < $value;
    }

    public function bucketForFlagKey(FeatureFlagKey $flagKey): int
    {
        $hash = crc32($flagKey->value());

        return (int) ($hash % 100);
    }
}
