<?php

namespace App\Modules\FeatureFlags\Infrastructure\Models;

use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagScope;
use App\Modules\FeatureFlags\Domain\Enums\FeatureFlagType;
use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $table = 'feature_flags';

    protected $fillable = [
        'key',
        'name',
        'description',
        'type',
        'scope',
        'enabled',
        'rollout_percentage',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'type' => FeatureFlagType::class,
        'scope' => FeatureFlagScope::class,
    ];
}
