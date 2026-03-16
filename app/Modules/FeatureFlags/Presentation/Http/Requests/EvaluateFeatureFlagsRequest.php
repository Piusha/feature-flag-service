<?php

namespace App\Modules\FeatureFlags\Presentation\Http\Requests;

use App\Http\Requests\ApiFormRequest;
use App\Modules\FeatureFlags\Application\DTO\EvaluateFlagsQuery;
use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;

class EvaluateFeatureFlagsRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function toQuery(): EvaluateFlagsQuery
    {
        $data = $this->validated();

        return new EvaluateFlagsQuery(
            context: new EvaluationContext(
                userId: $data['user_id'] ?? 'public',
            )
        );
    }
}
