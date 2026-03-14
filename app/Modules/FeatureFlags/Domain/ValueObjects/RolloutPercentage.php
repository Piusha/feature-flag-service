<?php

namespace App\Modules\FeatureFlags\Domain\ValueObjects;

use InvalidArgumentException;

final class RolloutPercentage
{
    public function __construct(private readonly int $value)
    {
        if ($value < 0 || $value > 100) {
            throw new InvalidArgumentException('Rollout percentage must be between 0 and 100.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
