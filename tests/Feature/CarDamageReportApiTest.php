<?php

namespace Tests\Feature;

use App\Modules\FeatureFlags\Infrastructure\Models\FeatureFlag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarDamageReportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_api_basic_flow(): void
    {
        FeatureFlag::query()->create([
            'key' => 'allow_report_editing',
            'name' => 'Allow Report Editing',
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => true,
        ]);

        $createResponse = $this->postJson('/api/reports', [
            'customer_name' => 'Jane Doe',
            'vehicle_registration' => 'REG1234',
            'vehicle_model' => 'Ford Focus',
            'damage_description' => 'Rear bumper cracked.',
            'severity' => 'high',
            'repair_estimate_amount' => 1250.50,
            'status' => 'submitted',
            'incident_date' => now()->subDay()->toDateString(),
            'incident_location' => 'Downtown',
        ]);

        $createResponse->assertCreated();
        $reportId = (int) $createResponse->json('id');

        $this->getJson('/api/reports')
            ->assertOk()
            ->assertJsonPath('data.0.id', $reportId);

        $this->getJson("/api/reports/{$reportId}")
            ->assertOk()
            ->assertJsonPath('id', $reportId);

        $this->putJson("/api/reports/{$reportId}?user_id=123", [
            'status' => 'reviewed',
        ])->assertOk()
            ->assertJsonPath('status', 'reviewed');
    }
}
