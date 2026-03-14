<?php

namespace App\Modules\EventLogs\Infrastructure\Repositories;

use App\Modules\EventLogs\Domain\Entities\EventLogEntry;
use App\Modules\EventLogs\Domain\Entities\EventLogPage;
use App\Modules\EventLogs\Domain\Repositories\EventLogRepository;
use App\Modules\EventLogs\Domain\ValueObjects\EventLogQueryFilters;
use App\Modules\EventLogs\Infrastructure\Models\EventLog;
use Illuminate\Database\Eloquent\Builder;

class EloquentEventLogRepository implements EventLogRepository
{
    public function create(EventLogEntry $entry): EventLogEntry
    {
        /** @var EventLog $created */
        $created = EventLog::query()->create([
            'event_name' => $entry->eventName,
            'aggregate_type' => $entry->aggregateType,
            'aggregate_id' => $entry->aggregateId,
            'actor_id' => $entry->actorId,
            'actor_type' => $entry->actorType,
            'context' => $entry->context,
            'payload' => $entry->payload,
            'occurred_at' => $entry->occurredAt,
        ]);

        return $this->toDomain($created);
    }

    public function paginate(EventLogQueryFilters $filters, int $perPage = 20, int $page = 1): EventLogPage
    {
        $query = EventLog::query()
            ->orderByDesc('occurred_at')
            ->orderByDesc('id');

        $this->applyFilters($query, $filters);

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        $items = [];

        foreach ($paginator->items() as $item) {
            /** @var EventLog $item */
            $items[] = $this->toDomain($item);
        }

        return new EventLogPage(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage(),
        );
    }

    public function findById(int $id): ?EventLogEntry
    {
        $eventLog = EventLog::query()->find($id);

        return $eventLog ? $this->toDomain($eventLog) : null;
    }

    private function applyFilters(Builder $query, EventLogQueryFilters $filters): void
    {
        if ($filters->eventName !== null && $filters->eventName !== '') {
            $query->where('event_name', $filters->eventName);
        }

        if ($filters->aggregateType !== null && $filters->aggregateType !== '') {
            $query->where('aggregate_type', $filters->aggregateType);
        }

        if ($filters->aggregateId !== null && $filters->aggregateId !== '') {
            $query->where('aggregate_id', $filters->aggregateId);
        }

        if ($filters->actorId !== null && $filters->actorId !== '') {
            $query->where('actor_id', $filters->actorId);
        }

        if ($filters->occurredFrom !== null) {
            $query->where('occurred_at', '>=', $filters->occurredFrom);
        }

        if ($filters->occurredTo !== null) {
            $query->where('occurred_at', '<=', $filters->occurredTo);
        }
    }

    private function toDomain(EventLog $eventLog): EventLogEntry
    {
        return new EventLogEntry(
            id: $eventLog->id,
            eventName: (string) $eventLog->event_name,
            aggregateType: (string) $eventLog->aggregate_type,
            aggregateId: (string) $eventLog->aggregate_id,
            actorId: $eventLog->actor_id ? (string) $eventLog->actor_id : null,
            actorType: $eventLog->actor_type ? (string) $eventLog->actor_type : null,
            context: is_array($eventLog->context) ? $eventLog->context : null,
            payload: is_array($eventLog->payload) ? $eventLog->payload : [],
            occurredAt: \DateTimeImmutable::createFromInterface($eventLog->occurred_at),
            createdAt: $eventLog->created_at ? \DateTimeImmutable::createFromInterface($eventLog->created_at) : null,
            updatedAt: $eventLog->updated_at ? \DateTimeImmutable::createFromInterface($eventLog->updated_at) : null,
        );
    }
}
