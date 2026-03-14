<?php

namespace App\Modules\EventLogs\Domain\Entities;

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
        public readonly \DateTimeImmutable $occurredAt,
        public readonly ?\DateTimeImmutable $createdAt = null,
        public readonly ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
