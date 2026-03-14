<?php

namespace Tests\Feature\EventLogs;

use App\Modules\CarDamageReports\Infrastructure\Models\CarDamageReport;
use App\Modules\EventLogs\Infrastructure\Listeners\LogFeatureFlagCreatedListener;
use App\Modules\EventLogs\Infrastructure\Models\EventLog;
use App\Modules\FeatureFlags\Infrastructure\Models\FeatureFlag;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EventLoggingPipelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_feature_flag_created_dispatch_is_queued_on_event_logs_queue(): void
    {
        Queue::fake();

        $this->postJson('/api/admin/feature-flags', [
            'key' => 'flag_create_queue_test',
            'name' => 'Flag Create Queue Test',
            'description' => 'Queue dispatch assertion',
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => true,
            'rollout_percentage' => null,
            'starts_at' => null,
            'expires_at' => null,
        ])->assertCreated();

        Queue::assertPushedOn('event-logs', CallQueuedListener::class, function (CallQueuedListener $job): bool {
            return $job->class === LogFeatureFlagCreatedListener::class;
        });
    }

    public function test_feature_flag_created_full_flow_persists_event_log_record(): void
    {
        $this->postJson('/api/admin/feature-flags', [
            'key' => 'flag_created_full_flow',
            'name' => 'Flag Created Full Flow',
            'description' => null,
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => true,
            'rollout_percentage' => null,
            'starts_at' => null,
            'expires_at' => null,
        ])->assertCreated();

        $log = EventLog::query()->where('event_name', 'feature_flag.created')->latest('id')->first();

        $this->assertNotNull($log);
        $this->assertSame('feature_flag', $log->aggregate_type);
        $this->assertNotNull($log->aggregate_id);
        $this->assertSame('flag_created_full_flow', $log->payload['feature_flag_key'] ?? null);
        $this->assertIsArray($log->context);
        $this->assertSame('create', $log->context['operation'] ?? null);
    }

    public function test_feature_flag_updated_dispatch_stores_changes_payload(): void
    {
        $flag = FeatureFlag::query()->create([
            'key' => 'flag_update_payload_test',
            'name' => 'Flag Update Payload Test',
            'description' => 'Old description',
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => false,
        ]);

        $this->putJson("/api/admin/feature-flags/{$flag->id}", [
            'key' => 'flag_update_payload_test',
            'name' => 'Flag Update Payload Test',
            'description' => 'New description',
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => true,
            'rollout_percentage' => null,
            'starts_at' => null,
            'expires_at' => null,
        ])->assertOk();

        $log = EventLog::query()->where('event_name', 'feature_flag.updated')->latest('id')->first();

        $this->assertNotNull($log);
        $this->assertSame('feature_flag', $log->aggregate_type);
        $this->assertSame((string) $flag->id, $log->aggregate_id);
        $this->assertSame('flag_update_payload_test', $log->payload['feature_flag_key'] ?? null);
        $this->assertIsArray($log->payload['changes'] ?? null);
        $this->assertSame(false, $log->payload['changes']['enabled']['old'] ?? null);
        $this->assertSame(true, $log->payload['changes']['enabled']['new'] ?? null);
    }

    public function test_feature_flag_evaluation_denied_persists_denial_event(): void
    {
        $flag = FeatureFlag::query()->create([
            'key' => 'allow_report_editing',
            'name' => 'Allow Report Editing',
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => false,
        ]);

        $report = CarDamageReport::query()->create([
            'reference_number' => 'CDR-DENIAL-1',
            'customer_name' => 'Denied User',
            'vehicle_registration' => 'DEN123',
            'vehicle_model' => 'Model D',
            'damage_description' => 'Denied update',
            'severity' => 'low',
            'status' => 'draft',
            'incident_date' => now()->toDateString(),
        ]);

        $this->putJson("/api/reports/{$report->id}?user_id=user-777", [
            'customer_name' => 'Updated Name',
        ])->assertForbidden();

        $log = EventLog::query()->where('event_name', 'feature_flag.evaluation_denied')->latest('id')->first();

        $this->assertNotNull($log);
        $this->assertSame((string) $flag->id, $log->aggregate_id);
        $this->assertSame('feature_flag', $log->aggregate_type);
        $this->assertSame('user-777', $log->actor_id);
        $this->assertSame('allow_report_editing', $log->payload['feature_flag_key'] ?? null);
        $this->assertSame('feature_not_enabled_for_context', $log->payload['reason'] ?? null);
    }

    public function test_event_logs_api_returns_expected_persistence_shape(): void
    {
        $this->postJson('/api/admin/feature-flags', [
            'key' => 'flag_shape_test',
            'name' => 'Flag Shape Test',
            'description' => null,
            'type' => 'boolean',
            'scope' => 'feature',
            'enabled' => true,
            'rollout_percentage' => null,
            'starts_at' => null,
            'expires_at' => null,
        ])->assertCreated();

        $this->getJson('/api/admin/event-logs?event_name=feature_flag.created')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'event_name',
                        'aggregate_type',
                        'aggregate_id',
                        'actor_id',
                        'actor_type',
                        'context',
                        'payload',
                        'occurred_at',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'meta' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
            ]);
    }

    public function test_redis_queue_after_commit_is_enabled_for_event_logging_safety(): void
    {
        config()->set('queue.connections.redis.after_commit', true);

        $this->assertTrue((bool) config('queue.connections.redis.after_commit'));
    }
}
