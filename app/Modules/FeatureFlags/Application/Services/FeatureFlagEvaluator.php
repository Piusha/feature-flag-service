<?php

namespace App\Modules\FeatureFlags\Application\Services;

use App\Modules\FeatureFlags\Application\Contracts\FeatureFlagEvaluatorInterface;
use App\Modules\FeatureFlags\Application\Contracts\PercentageRolloutServiceInterface;
use App\Modules\FeatureFlags\Domain\Entities\FeatureFlagEntity;
use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagType;
use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;
use App\SharedKernel\Domain\Clock;
use Throwable;

class FeatureFlagEvaluator implements FeatureFlagEvaluatorInterface
{
    public function __construct(
        private readonly PercentageRolloutServiceInterface $percentageRollout,
        private readonly Clock $clock,
    ) {}

    public function evaluate(FeatureFlagEntity $featureFlag, EvaluationContext $context): bool
    {
        try {
            if (! $featureFlag->enabled()) {
                return false;
            }

            $now = $this->clock->now();
            $schedule = $featureFlag->schedule();



            if ($schedule->isBeforeStart($now)) {
                return false;
            }

            if ($schedule->isAfterExpiry($now)) {
                return false;
            }

            if ($featureFlag->type() === FeatureFlagType::RULE_BASED) {
                return $this->percentageRollout->isInRollout(
                    $featureFlag->key(),
                    $featureFlag->rolloutPercentage()
                );
            }

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
