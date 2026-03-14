<?php

namespace Database\Seeders;

use App\Modules\FeatureFlags\Infrastructure\Models\FeatureFlag;
use Illuminate\Database\Seeder;

class FeatureFlagSeeder extends Seeder
{
    public function run(): void
    {
        $flags = [
            [
                'key' => 'show_damage_photos_section',
                'name' => 'Show Damage Photos Section',
                'description' => 'Show or hide photos section in report UI.',
                'type' => 'boolean',
                'scope' => 'component',
                'enabled' => true,
                'rollout_percentage' => null,
                'starts_at' => null,
                'expires_at' => null,
            ],
            [
                'key' => 'show_repair_estimate_panel',
                'name' => 'Show Repair Estimate Panel',
                'description' => 'Show or hide repair estimate block.',
                'type' => 'boolean',
                'scope' => 'component',
                'enabled' => true,
                'rollout_percentage' => null,
                'starts_at' => null,
                'expires_at' => null,
            ],
            [
                'key' => 'show_report_history_timeline',
                'name' => 'Show Report History Timeline',
                'description' => 'Show or hide report history timeline.',
                'type' => 'boolean',
                'scope' => 'component',
                'enabled' => false,
                'rollout_percentage' => null,
                'starts_at' => null,
                'expires_at' => null,
            ],
            [
                'key' => 'allow_report_editing',
                'name' => 'Allow Report Editing',
                'description' => 'Allow updates of existing reports.',
                'type' => 'boolean',
                'scope' => 'feature',
                'enabled' => true,
                'rollout_percentage' => null,
                'starts_at' => null,
                'expires_at' => null,
            ],
            [
                'key' => 'allow_photo_upload',
                'name' => 'Allow Photo Upload',
                'description' => 'Allow uploading report photos.',
                'type' => 'boolean',
                'scope' => 'feature',
                'enabled' => true,
                'rollout_percentage' => null,
                'starts_at' => null,
                'expires_at' => null,
            ],
            [
                'key' => 'enable_damage_report_v2_form',
                'name' => 'Enable Damage Report V2 Form',
                'description' => 'Progressively rollout v2 damage report form.',
                'type' => 'rule_based',
                'scope' => 'page',
                'enabled' => true,
                'rollout_percentage' => 50,
                'starts_at' => null,
                'expires_at' => null,
            ],
        ];

        foreach ($flags as $flag) {
            FeatureFlag::query()->updateOrCreate(['key' => $flag['key']], $flag);
        }
    }
}
