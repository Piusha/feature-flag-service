<?php

namespace App\Modules\FeatureFlags\Presentation\Http\Requests;

use App\Http\Requests\ApiFormRequest;
use App\Modules\FeatureFlags\Application\DTO\CreateFeatureFlagCommand;
use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagScope;
use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagType;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Domain\ValueObjects\FlagSchedule;
use App\Modules\FeatureFlags\Domain\ValueObjects\RolloutPercentage;
use Illuminate\Validation\Rule;

class StoreFeatureFlagRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:255', 'unique:feature_flags,key'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['boolean', 'rule_based'])],
            'scope' => ['required', Rule::in(['component', 'feature', 'page'])],
            'enabled' => ['required', 'boolean'],
            'rollout_percentage' => ['nullable', 'integer', 'between:0,100'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
        ];
    }

    public function toCommand(): CreateFeatureFlagCommand
    {
        $data = $this->validated();

        return new CreateFeatureFlagCommand(
            key: new FeatureFlagKey($data['key']),
            name: $data['name'],
            description: $data['description'] ?? null,
            type: FeatureFlagType::from($data['type']),
            scope: FeatureFlagScope::from($data['scope']),
            enabled: (bool) $data['enabled'],
            rolloutPercentage: isset($data['rollout_percentage']) ? new RolloutPercentage((int) $data['rollout_percentage']) : null,
            schedule: new FlagSchedule(
                startsAt: isset($data['starts_at']) ? new \DateTimeImmutable($data['starts_at']) : null,
                expiresAt: isset($data['expires_at']) ? new \DateTimeImmutable($data['expires_at']) : null,
            ),
        );
    }
}
