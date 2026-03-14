<?php

namespace App\Modules\EventLogs\Domain\ValueObjects;

final class EventLogQueryFilters
{
    public function __construct(
        public readonly ?string $eventName,
        public readonly ?string $aggregateType,
        public readonly ?string $aggregateId,
        public readonly ?string $actorId,
        public readonly ?\DateTimeImmutable $occurredFrom,
        public readonly ?\DateTimeImmutable $occurredTo,
    ) {}
}
