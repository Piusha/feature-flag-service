<?php

namespace App\Modules\FeatureFlags\Application\Contracts;

use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;

interface FeatureFlagCacheInterface
{
    public function keyForContext(EvaluationContext $context): string;

    public function getOrRemember(EvaluationContext $context, int $ttlSeconds, callable $resolver): array;

    public function invalidateAllContexts(): void;
}
