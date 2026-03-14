<?php

namespace App\Modules\FeatureFlags\Application\Services;

use App\Modules\FeatureFlags\Application\Contracts\PercentageRolloutServiceInterface;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Domain\ValueObjects\RolloutPercentage;

class PercentageRolloutService implements PercentageRolloutServiceInterface
{
    public function isUserInRollout(FeatureFlagKey $flagKey, string $userId, ?RolloutPercentage $percentage): bool
    {
        $value = $percentage?->value() ?? 0;

        if ($value <= 0) {
            return false;
        }

        if ($value >= 100) {
            return true;
        }

        $bucket = $this->bucketForUser($flagKey, $userId);

        return $bucket < $value;
    }

    public function bucketForUser(FeatureFlagKey $flagKey, string $userId): int
    {
        $hash = crc32($flagKey->value().':'.$userId);

        return (int) ($hash % 100);
    }
}
