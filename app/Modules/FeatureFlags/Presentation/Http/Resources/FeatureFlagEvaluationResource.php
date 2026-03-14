<?php

namespace App\Modules\FeatureFlags\Presentation\Http\Resources;

use App\Modules\FeatureFlags\Application\DTO\FeatureFlagEvaluationResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin FeatureFlagEvaluationResponse */
class FeatureFlagEvaluationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'context' => [
                'user_id' => $this->resource->userId,
            ],
            'flags' => $this->resource->flags,
            'evaluated_at' => $this->resource->evaluatedAt,
            'ttl_seconds' => $this->resource->ttlSeconds,
        ];
    }
}
