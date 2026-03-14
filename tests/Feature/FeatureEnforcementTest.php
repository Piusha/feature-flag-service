<?php

namespace Tests\Feature;

use App\Modules\CarDamageReports\Infrastructure\Models\CarDamageReport;
use App\Modules\FeatureFlags\Infrastructure\Models\FeatureFlag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FeatureEnforcementTest extends TestCase
{
    use RefreshDatabase;

    public function test_allow_report_editing_enforcement_returns_403_when_disabled(): void
    {
        FeatureFlag::query()->create([
            'key' => 'allow_report_editing',
            'name' => 'Allow Report Editing',
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => false,
        ]);

        $report = CarDamageReport::query()->create([
            'reference_number' => 'CDR-REF-1',
            'customer_name' => 'Alice',
            'vehicle_registration' => 'ABC123',
            'vehicle_model' => 'Model X',
            'damage_description' => 'Front bumper damage',
            'severity' => 'medium',
            'status' => 'draft',
            'incident_date' => now()->toDateString(),
        ]);

        $response = $this->putJson("/api/reports/{$report->id}?user_id=123", [
            'customer_name' => 'Alice Updated',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'code' => 'FEATURE_DISABLED',
                'message' => 'This feature is currently disabled.',
            ]);
    }

    public function test_allow_photo_upload_enforcement_returns_403_when_disabled(): void
    {
        Storage::fake('public');

        FeatureFlag::query()->create([
            'key' => 'allow_photo_upload',
            'name' => 'Allow Photo Upload',
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => false,
        ]);

        $report = CarDamageReport::query()->create([
            'reference_number' => 'CDR-REF-2',
            'customer_name' => 'Bob',
            'vehicle_registration' => 'XYZ987',
            'vehicle_model' => 'Model Y',
            'damage_description' => 'Door scratch',
            'severity' => 'low',
            'status' => 'draft',
            'incident_date' => now()->toDateString(),
        ]);

        $response = $this->postJson("/api/reports/{$report->id}/photos?user_id=456", [
            'photo' => UploadedFile::fake()->create('damage.jpg', 64, 'image/jpeg'),
            'user_id' => '456',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'code' => 'FEATURE_DISABLED',
                'message' => 'This feature is currently disabled.',
            ]);
    }
}
