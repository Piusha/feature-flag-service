<?php

namespace App\Modules\FeatureFlags\Presentation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\FeatureFlags\Application\Contracts\EvaluateFeatureFlagsUseCaseInterface;
use App\Modules\FeatureFlags\Presentation\Http\Resources\FeatureFlagEvaluationResource;
use App\Modules\FeatureFlags\Presentation\Http\Requests\EvaluateFeatureFlagsRequest;
use Illuminate\Http\JsonResponse;

class FeatureFlagEvaluationController extends Controller
{
    public function __construct(private readonly EvaluateFeatureFlagsUseCaseInterface $evaluateFeatureFlags)
    {
    }

    public function __invoke(EvaluateFeatureFlagsRequest $request): JsonResponse
    {
        $result = $this->evaluateFeatureFlags->handle($request->toQuery());

        return response()->json((new FeatureFlagEvaluationResource($result))->resolve());
    }
}
