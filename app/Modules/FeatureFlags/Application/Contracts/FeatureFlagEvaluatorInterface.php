<?php

namespace App\Modules\FeatureFlags\Application\Contracts;

use App\Modules\FeatureFlags\Domain\Entities\FeatureFlagEntity;
use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;

interface FeatureFlagEvaluatorInterface
{
    public function evaluate(FeatureFlagEntity $featureFlag, EvaluationContext $context): bool;
}
