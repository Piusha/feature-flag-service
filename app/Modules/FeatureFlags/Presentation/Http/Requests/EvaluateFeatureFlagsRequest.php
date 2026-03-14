<?php

namespace App\Modules\FeatureFlags\Presentation\Http\Requests;

use App\Modules\FeatureFlags\Application\DTO\EvaluateFlagsQuery;
use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;
use Illuminate\Foundation\Http\FormRequest;

class EvaluateFeatureFlagsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string', 'max:255'],
        ];
    }

    public function toQuery(): EvaluateFlagsQuery
    {
        $data = $this->validated();

        return new EvaluateFlagsQuery(
            context: new EvaluationContext(
                userId: $data['user_id'],
            )
        );
    }
}
