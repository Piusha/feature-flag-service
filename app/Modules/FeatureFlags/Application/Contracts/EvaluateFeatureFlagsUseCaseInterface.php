<?php

namespace App\Modules\FeatureFlags\Application\Contracts;

use App\Modules\FeatureFlags\Application\DTO\EvaluateFlagsQuery;
use App\Modules\FeatureFlags\Application\DTO\FeatureFlagEvaluationResponse;

interface EvaluateFeatureFlagsUseCaseInterface
{
    public function handle(EvaluateFlagsQuery $query): FeatureFlagEvaluationResponse;
}
