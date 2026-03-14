<?php

namespace App\Modules\EventLogs\Domain\Repositories;

use App\Modules\EventLogs\Domain\Entities\EventLogEntry;
use App\Modules\EventLogs\Domain\Entities\EventLogPage;
use App\Modules\EventLogs\Domain\ValueObjects\EventLogQueryFilters;

interface EventLogRepository
{
    public function create(EventLogEntry $entry): EventLogEntry;

    public function paginate(EventLogQueryFilters $filters, int $perPage = 20, int $page = 1): EventLogPage;

    public function findById(int $id): ?EventLogEntry;
}
