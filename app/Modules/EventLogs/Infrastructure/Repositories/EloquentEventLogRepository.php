<?php

namespace App\Modules\EventLogs\Infrastructure\Repositories;

use App\Modules\EventLogs\Domain\Entities\EventLogEntry;
use App\Modules\EventLogs\Domain\Repositories\EventLogRepository;
use App\Modules\EventLogs\Infrastructure\Models\EventLog;
use Carbon\CarbonImmutable;

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

        return new EventLogEntry(
            id: $created->id,
            eventName: (string) $created->event_name,
            aggregateType: (string) $created->aggregate_type,
            aggregateId: (string) $created->aggregate_id,
            actorId: $created->actor_id ? (string) $created->actor_id : null,
            actorType: $created->actor_type ? (string) $created->actor_type : null,
            context: is_array($created->context) ? $created->context : null,
            payload: is_array($created->payload) ? $created->payload : [],
            occurredAt: CarbonImmutable::instance($created->occurred_at),
            createdAt: $created->created_at ? CarbonImmutable::instance($created->created_at) : null,
            updatedAt: $created->updated_at ? CarbonImmutable::instance($created->updated_at) : null,
        );
    }
}
