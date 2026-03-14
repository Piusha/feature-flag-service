<?php

namespace App\Modules\EventLogs\Application\Actions;

use App\Modules\EventLogs\Application\DTO\StoreEventLogData;
use App\Modules\EventLogs\Domain\Entities\EventLogEntry;
use App\Modules\EventLogs\Domain\Repositories\EventLogRepository;

final class StoreEventLogAction
{
    public function __construct(private readonly EventLogRepository $repository)
    {
    }

    public function __invoke(StoreEventLogData $data): EventLogEntry
    {
        $entry = new EventLogEntry(
            id: null,
            eventName: $data->eventName,
            aggregateType: $data->aggregateType,
            aggregateId: $data->aggregateId,
            actorId: $data->actorId,
            actorType: $data->actorType,
            context: $data->context,
            payload: $data->payload,
            occurredAt: $data->occurredAt,
        );

        return $this->repository->create($entry);
    }
}
