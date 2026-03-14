<?php

namespace App\Modules\EventLogs\Domain\Entities;

final class EventLogPage
{
    /**
     * @param EventLogEntry[] $items
     */
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $perPage,
        public readonly int $currentPage,
        public readonly int $lastPage,
    ) {}
}
