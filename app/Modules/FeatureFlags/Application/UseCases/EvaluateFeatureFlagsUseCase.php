<?php

namespace App\Modules\FeatureFlags\Application\UseCases;

use App\Modules\FeatureFlags\Application\DTO\EvaluateFlagsQuery;
use App\Modules\FeatureFlags\Application\DTO\FeatureFlagEvaluationResponse;
use App\Modules\FeatureFlags\Application\Contracts\EvaluateFeatureFlagsUseCaseInterface;
use App\Modules\FeatureFlags\Application\Contracts\FeatureFlagCacheInterface;
use App\Modules\FeatureFlags\Application\Contracts\FeatureFlagEvaluatorInterface;
use App\Modules\FeatureFlags\Domain\Repositories\FeatureFlagRepository;
use App\SharedKernel\Domain\Clock;
use Illuminate\Support\Facades\Log;

class EvaluateFeatureFlagsUseCase implements EvaluateFeatureFlagsUseCaseInterface
{
    public function __construct(
        private readonly FeatureFlagRepository $featureFlags,
        private readonly FeatureFlagEvaluatorInterface $evaluator,
        private readonly FeatureFlagCacheInterface $cache,
        private readonly Clock $clock,
    ) {}

    public function handle(EvaluateFlagsQuery $query): FeatureFlagEvaluationResponse
    {
        $ttlSeconds = (int) config('feature_flags.cache_ttl_seconds', 1);
        $context = $query->context;

        Log::info('Evaluating feature flags', [
            'context' => $context,
            'ttlSeconds' => $ttlSeconds,
        ]);

        $flags = $this->cache->getOrRemember($context, $ttlSeconds, function () use ($context): array {
            $evaluated = [];

            foreach ($this->featureFlags->all() as $featureFlag) {
                $evaluated[$featureFlag->key()->value()] = $this->evaluator->evaluate($featureFlag, $context);
            }

            return $evaluated;
        });
        Log::info('Feature flags evaluated', [
            'flags' => $flags,
        ]);

        return new FeatureFlagEvaluationResponse(
            userId: $context->userId(),
            flags: $flags,
            evaluatedAt: $this->clock->now()->format(DATE_ATOM),
            ttlSeconds: $ttlSeconds,
        );
    }
}
