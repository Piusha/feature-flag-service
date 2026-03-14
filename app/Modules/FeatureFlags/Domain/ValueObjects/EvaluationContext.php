<?php

namespace App\Modules\FeatureFlags\Domain\ValueObjects;

final class EvaluationContext
{
    public function __construct(
        private readonly string $userId,
    ) {}

    public function userId(): string
    {
        return $this->userId;
    }
}
