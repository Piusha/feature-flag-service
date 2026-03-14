<?php

namespace App\Modules\EventLogs\Domain\Entities;

use Carbon\CarbonImmutable;

final class EventLogEntry
{
    /**
     * @param array<string, mixed>|null $context
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public readonly ?int $id,
        public readonly string $eventName,
        public readonly string $aggregateType,
        public readonly string $aggregateId,
        public readonly ?string $actorId,
        public readonly ?string $actorType,
        public readonly ?array $context,
        public readonly array $payload,
        public readonly CarbonImmutable $occurredAt,
        public readonly ?CarbonImmutable $createdAt = null,
        public readonly ?CarbonImmutable $updatedAt = null,
    ) {}
}
