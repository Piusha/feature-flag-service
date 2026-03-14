<?php

namespace App\Modules\EventLogs\Domain\Repositories;

use App\Modules\EventLogs\Domain\Entities\EventLogEntry;

interface EventLogRepository
{
    public function create(EventLogEntry $entry): EventLogEntry;
}
