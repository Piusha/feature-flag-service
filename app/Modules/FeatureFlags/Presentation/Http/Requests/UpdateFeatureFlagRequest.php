<?php

namespace App\Modules\FeatureFlags\Presentation\Http\Requests;

use App\Http\Requests\ApiFormRequest;
use App\Modules\FeatureFlags\Application\DTO\UpdateFeatureFlagCommand;
use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagScope;
use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagType;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Domain\ValueObjects\RolloutPercentage;
use Illuminate\Validation\Rule;

class UpdateFeatureFlagRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $featureFlagId = (int) $this->route('id');

        return [
            'key' => ['required', 'string', 'max:255', Rule::unique('feature_flags', 'key')->ignore($featureFlagId)],
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

    public function toCommand(): UpdateFeatureFlagCommand
    {
        $data = $this->validated();
        $id = (int) $this->route('id');
        $input = $this->all();

        return new UpdateFeatureFlagCommand(
            id: $id,
            key: new FeatureFlagKey($data['key']),
            name: $data['name'],
            description: $data['description'] ?? null,
            hasDescription: array_key_exists('description', $input),
            type: FeatureFlagType::from($data['type']),
            scope: FeatureFlagScope::from($data['scope']),
            enabled: (bool) $data['enabled'],
            rolloutPercentage: array_key_exists('rollout_percentage', $data) && $data['rollout_percentage'] !== null
                ? new RolloutPercentage((int) $data['rollout_percentage'])
                : null,
            hasRolloutPercentage: array_key_exists('rollout_percentage', $input),
            startsAt: array_key_exists('starts_at', $data) && $data['starts_at'] !== null
                ? new \DateTimeImmutable($data['starts_at'])
                : null,
            hasStartsAt: array_key_exists('starts_at', $input),
            expiresAt: array_key_exists('expires_at', $data) && $data['expires_at'] !== null
                ? new \DateTimeImmutable($data['expires_at'])
                : null,
            hasExpiresAt: array_key_exists('expires_at', $input),
        );
    }
}
