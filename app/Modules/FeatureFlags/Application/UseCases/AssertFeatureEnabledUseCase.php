<?php

namespace App\Modules\FeatureFlags\Application\UseCases;

use App\Modules\FeatureFlags\Application\Contracts\AssertFeatureEnabledUseCaseInterface;
use App\Modules\FeatureFlags\Application\Contracts\FeatureFlagEvaluatorInterface;
use App\Modules\FeatureFlags\Domain\Repositories\FeatureFlagRepository;
use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;

class AssertFeatureEnabledUseCase implements AssertFeatureEnabledUseCaseInterface
{
    public function __construct(
        private readonly FeatureFlagRepository $featureFlags,
        private readonly FeatureFlagEvaluatorInterface $evaluator,
    ) {
    }

    public function handle(FeatureFlagKey $featureKey, EvaluationContext $context): bool
    {
        $featureFlag = $this->featureFlags->findByKey($featureKey);

        if ($featureFlag === null) {
            return false;
        }

        return $this->evaluator->evaluate($featureFlag, $context);
    }
}
