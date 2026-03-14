<?php

namespace App\Modules\FeatureFlags\Application\DTO;

final class FeatureFlagEvaluationResponse
{
    /**
     * @param array<string, bool> $flags
     */
    public function __construct(
        public readonly string $userId,
        public readonly array $flags,
        public readonly string $evaluatedAt,
        public readonly int $ttlSeconds,
    ) {}
}
