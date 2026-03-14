<?php

namespace App\Modules\FeatureFlags\Domain\ValueObjects;

use InvalidArgumentException;

final class FeatureFlagKey
{
    public function __construct(private readonly string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('Feature flag key cannot be empty.');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
