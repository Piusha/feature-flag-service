<?php

namespace App\Modules\EventLogs\Application\UseCases;

use App\Modules\EventLogs\Domain\Entities\EventLogEntry;
use App\Modules\EventLogs\Domain\Entities\EventLogPage;
use App\Modules\EventLogs\Domain\Repositories\EventLogRepository;
use App\Modules\EventLogs\Domain\ValueObjects\EventLogQueryFilters;

final class QueryEventLogsUseCase
{
    public function __construct(private readonly EventLogRepository $eventLogs)
    {
    }

    public function list(EventLogQueryFilters $filters, int $perPage = 20, int $page = 1): EventLogPage
    {
        return $this->eventLogs->paginate($filters, $perPage, $page);
    }

    public function find(int $id): ?EventLogEntry
    {
        return $this->eventLogs->findById($id);
    }
}
