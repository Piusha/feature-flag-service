<?php

namespace App\Modules\FeatureFlags\Application\Contracts;

use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;

interface AssertFeatureEnabledUseCaseInterface
{
    public function handle(FeatureFlagKey $featureKey, EvaluationContext $context): bool;
}
