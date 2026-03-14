<?php

namespace Tests\Feature\EventLogs;

use App\Modules\EventLogs\Application\UseCases\QueryEventLogsUseCase;
use App\Modules\EventLogs\Infrastructure\Models\EventLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventLogsReadApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_logs_index_supports_filters_and_pagination(): void
    {
        EventLog::query()->create([
            'event_name' => 'feature_flag.updated',
            'aggregate_type' => 'feature_flag',
            'aggregate_id' => '10',
            'actor_id' => 'user-1',
            'actor_type' => 'user',
            'context' => ['source' => 'test'],
            'payload' => ['changes' => ['enabled' => ['old' => false, 'new' => true]]],
            'occurred_at' => '2026-03-14 12:00:00',
        ]);

        EventLog::query()->create([
            'event_name' => 'feature_flag.created',
            'aggregate_type' => 'feature_flag',
            'aggregate_id' => '11',
            'actor_id' => 'user-2',
            'actor_type' => 'user',
            'context' => ['source' => 'test'],
            'payload' => ['feature_flag_key' => 'other'],
            'occurred_at' => '2026-03-13 12:00:00',
        ]);

        $this->getJson('/api/admin/event-logs?event_name=feature_flag.updated&aggregate_type=feature_flag&aggregate_id=10&actor_id=user-1&date_from=2026-03-14&date_to=2026-03-14&per_page=10&page=1')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.event_name', 'feature_flag.updated')
            ->assertJsonPath('data.0.aggregate_id', '10');
    }

    public function test_event_logs_show_returns_single_event_log(): void
    {
        $log = EventLog::query()->create([
            'event_name' => 'feature_flag.deleted',
            'aggregate_type' => 'feature_flag',
            'aggregate_id' => '22',
            'actor_id' => null,
            'actor_type' => null,
            'context' => ['operation' => 'delete'],
            'payload' => ['feature_flag_key' => 'cleanup-flag'],
            'occurred_at' => '2026-03-14 13:00:00',
        ]);

        $this->getJson("/api/admin/event-logs/{$log->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $log->id)
            ->assertJsonPath('data.event_name', 'feature_flag.deleted')
            ->assertJsonPath('data.aggregate_id', '22');
    }

    public function test_event_logs_index_validates_date_range_filter(): void
    {
        $this->getJson('/api/admin/event-logs?date_from=2026-03-15&date_to=2026-03-14')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['date_to']);
    }

    public function test_event_logs_show_requires_positive_id(): void
    {
        $this->getJson('/api/admin/event-logs/0')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['id']);
    }

    public function test_query_event_logs_use_case_is_resolvable_from_container(): void
    {
        $resolved = $this->app->make(QueryEventLogsUseCase::class);

        $this->assertInstanceOf(QueryEventLogsUseCase::class, $resolved);
    }
}
