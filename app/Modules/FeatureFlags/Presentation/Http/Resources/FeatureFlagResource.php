<?php

namespace App\Modules\FeatureFlags\Presentation\Http\Resources;

use App\Modules\FeatureFlags\Application\DTO\FeatureFlagResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin FeatureFlagResponse */
class FeatureFlagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->resource->toArray();
    }
}
