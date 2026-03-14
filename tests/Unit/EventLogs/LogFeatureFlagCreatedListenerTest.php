<?php

namespace Tests\Unit\EventLogs;

use App\Modules\EventLogs\Application\Actions\StoreEventLogAction;
use App\Modules\EventLogs\Domain\Entities\EventLogEntry;
use App\Modules\EventLogs\Domain\Entities\EventLogPage;
use App\Modules\EventLogs\Domain\Repositories\EventLogRepository;
use App\Modules\EventLogs\Domain\ValueObjects\EventLogQueryFilters;
use App\Modules\EventLogs\Infrastructure\Listeners\LogFeatureFlagCreatedListener;
use App\Modules\EventLogs\Infrastructure\Mappers\FeatureFlagEventLogDataMapper;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagCreated;
use Tests\TestCase;

class LogFeatureFlagCreatedListenerTest extends TestCase
{
    public function test_listener_uses_store_event_log_action_with_normalized_data(): void
    {
        $repository = new class implements EventLogRepository
        {
            public ?EventLogEntry $capturedEntry = null;

            public function create(EventLogEntry $entry): EventLogEntry
            {
                $this->capturedEntry = $entry;

                return new EventLogEntry(
                    id: 1,
                    eventName: $entry->eventName,
                    aggregateType: $entry->aggregateType,
                    aggregateId: $entry->aggregateId,
                    actorId: $entry->actorId,
                    actorType: $entry->actorType,
                    context: $entry->context,
                    payload: $entry->payload,
                    occurredAt: $entry->occurredAt,
                    createdAt: new \DateTimeImmutable(),
                    updatedAt: null,
                );
            }

            public function paginate(EventLogQueryFilters $filters, int $perPage = 20, int $page = 1): EventLogPage
            {
                return new EventLogPage(items: [], total: 0, perPage: $perPage, currentPage: $page, lastPage: 1);
            }

            public function findById(int $id): ?EventLogEntry
            {
                return null;
            }
        };

        $listener = new LogFeatureFlagCreatedListener(
            storeEventLog: new StoreEventLogAction($repository),
            mapper: new FeatureFlagEventLogDataMapper(),
        );

        $event = new FeatureFlagCreated(
            aggregateId: 42,
            featureFlagKey: 'internal_listener_test',
            actorId: 'user-1',
            actorType: 'user',
            context: ['source' => 'unit-test'],
            occurredAt: new \DateTimeImmutable(),
        );

        $listener->handle($event);

        $this->assertNotNull($repository->capturedEntry);
        $this->assertSame('feature_flag.created', $repository->capturedEntry->eventName);
        $this->assertSame('feature_flag', $repository->capturedEntry->aggregateType);
        $this->assertSame('42', $repository->capturedEntry->aggregateId);
        $this->assertSame('user-1', $repository->capturedEntry->actorId);
        $this->assertSame('internal_listener_test', $repository->capturedEntry->payload['feature_flag_key'] ?? null);
        $this->assertSame('unit-test', $repository->capturedEntry->context['source'] ?? null);
    }
}
