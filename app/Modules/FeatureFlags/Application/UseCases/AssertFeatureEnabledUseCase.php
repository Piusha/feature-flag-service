<?php

namespace App\Modules\FeatureFlags\Application\UseCases;

use App\Modules\FeatureFlags\Application\Contracts\AssertFeatureEnabledUseCaseInterface;
use App\Modules\FeatureFlags\Application\Contracts\FeatureFlagEvaluatorInterface;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagEvaluationDenied;
use App\Modules\FeatureFlags\Domain\Repositories\FeatureFlagRepository;
use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\SharedKernel\Domain\Clock;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class AssertFeatureEnabledUseCase implements AssertFeatureEnabledUseCaseInterface
{
    public function __construct(
        private readonly FeatureFlagRepository $featureFlags,
        private readonly FeatureFlagEvaluatorInterface $evaluator,
        private readonly Clock $clock,
    ) {}

    public function handle(FeatureFlagKey $featureKey, EvaluationContext $context): bool
    {
        $featureFlag = $this->featureFlags->findByKey($featureKey);

        if ($featureFlag === null) {
            return false;
        }

        Log::info('Evaluating feature flag', [
            'featureFlag' => $featureFlag,
            'context' => $context,
        ]);

        $enabled = $this->evaluator->evaluate($featureFlag, $context);

        if (! $enabled && $featureFlag->id() !== null) {
            Event::dispatch(new FeatureFlagEvaluationDenied(
                aggregateId: $featureFlag->id(),
                featureFlagKey: $featureKey->value(),
                reason: 'feature_not_enabled_for_context',
                actorId: $context->userId(),
                actorType: 'user',
                context: [
                    'module' => 'feature_flags',
                    'operation' => 'assert_feature_enabled',
                    'user_id' => $context->userId(),
                ],
                occurredAt: $this->clock->now(),
            ));
        }
        Log::info('Feature flag evaluated', [
            'featureFlag' => $featureFlag,
            'context' => $context,
            'enabled' => $enabled,
        ]);

        return $enabled;
    }
}
