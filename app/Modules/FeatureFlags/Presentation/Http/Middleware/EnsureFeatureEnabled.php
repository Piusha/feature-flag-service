<?php

namespace App\Modules\FeatureFlags\Presentation\Http\Middleware;

use App\Modules\FeatureFlags\Application\Contracts\AssertFeatureEnabledUseCaseInterface;
use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    public function __construct(private readonly AssertFeatureEnabledUseCaseInterface $assertFeatureEnabled) {}

    public function handle(Request $request, Closure $next, string $featureKey): Response|JsonResponse
    {
        $userId = (string) $request->input('user_id', $request->query('user_id', 'anonymous'));

        $context = new EvaluationContext($userId);
        $isEnabled = $this->assertFeatureEnabled->handle(new FeatureFlagKey($featureKey), $context);

        if (! $isEnabled) {
            return response()->json([
                'code' => 'FEATURE_DISABLED',
                'message' => 'This feature is currently disabled.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
