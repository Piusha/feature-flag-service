<?php

namespace App\Modules\EventLogs\Application\DTO;

use Carbon\CarbonImmutable;

final class StoreEventLogData
{
    /**
     * @param array<string, mixed>|null $context
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public readonly string $eventName,
        public readonly string $aggregateType,
        public readonly string $aggregateId,
        public readonly ?string $actorId,
        public readonly ?string $actorType,
        public readonly ?array $context,
        public readonly array $payload,
        public readonly CarbonImmutable $occurredAt,
    ) {}
}
