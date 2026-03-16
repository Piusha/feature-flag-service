<?php

namespace Tests\Feature;

use App\Modules\FeatureFlags\Application\Contracts\PercentageRolloutServiceInterface;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Infrastructure\Models\FeatureFlag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureFlagEvaluationTest extends TestCase
{
    use RefreshDatabase;

    public function test_boolean_flag_evaluates_true_when_enabled(): void
    {
        FeatureFlag::query()->create([
            'key' => 'show_damage_photos_section',
            'name' => 'Show Damage Photos Section',
            'type' => 'boolean',
            'scope' => 'component',
            'enabled' => true,
        ]);

        $response = $this->getJson('/api/feature-flags/evaluate?user_id=123');

        $response->assertOk()
            ->assertJsonPath('flags.show_damage_photos_section', true);
    }

    public function test_scheduled_activation_returns_false_before_start(): void
    {
        FeatureFlag::query()->create([
            'key' => 'allow_report_editing',
            'name' => 'Allow Report Editing',
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => true,
            'starts_at' => now()->addHour(),
        ]);

        $response = $this->getJson('/api/feature-flags/evaluate?user_id=123');

        $response->assertOk()
            ->assertJsonPath('flags.allow_report_editing', false);
    }

    public function test_scheduled_expiration_returns_false_after_expiry(): void
    {
        FeatureFlag::query()->create([
            'key' => 'allow_photo_upload',
            'name' => 'Allow Photo Upload',
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => true,
            'expires_at' => now()->subHour(),
        ]);

        $response = $this->getJson('/api/feature-flags/evaluate?user_id=123');

        $response->assertOk()
            ->assertJsonPath('flags.allow_photo_upload', false);
    }


    public function test_cache_is_invalidated_after_flag_update(): void
    {
        $flag = FeatureFlag::query()->create([
            'key' => 'show_report_history_timeline',
            'name' => 'Show Report History Timeline',
            'type' => 'boolean',
            'scope' => 'component',
            'enabled' => false,
        ]);

        $this->getJson('/api/feature-flags/evaluate?user_id=u-1')
            ->assertJsonPath('flags.show_report_history_timeline', false);

        $this->putJson("/api/admin/feature-flags/{$flag->id}", [
            'key' => 'show_report_history_timeline',
            'name' => 'Show Report History Timeline',
            'description' => null,
            'type' => 'boolean',
            'scope' => 'component',
            'enabled' => true,
            'rollout_percentage' => null,
            'starts_at' => null,
            'expires_at' => null,
        ])->assertOk();

        $this->getJson('/api/feature-flags/evaluate?user_id=u-1')
            ->assertJsonPath('flags.show_report_history_timeline', true);
    }
}
