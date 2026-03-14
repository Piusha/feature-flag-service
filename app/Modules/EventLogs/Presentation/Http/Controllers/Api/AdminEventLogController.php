<?php

namespace App\Modules\EventLogs\Presentation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\EventLogs\Application\UseCases\QueryEventLogsUseCase;
use App\Modules\EventLogs\Presentation\Http\Requests\ListEventLogsRequest;
use App\Modules\EventLogs\Presentation\Http\Requests\ShowEventLogRequest;
use App\Modules\EventLogs\Presentation\Http\Resources\EventLogResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminEventLogController extends Controller
{
    public function __construct(private readonly QueryEventLogsUseCase $eventLogs)
    {
    }

    public function index(ListEventLogsRequest $request): JsonResponse
    {
        $page = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 20);
        $result = $this->eventLogs->list($request->toFilters(), $perPage, $page);
        $data = array_map(
            fn ($item): array => (new EventLogResource($item))->resolve(),
            $result->items
        );

        return response()->json([
            'data' => $data,
            'meta' => [
                'total' => $result->total,
                'per_page' => $result->perPage,
                'current_page' => $result->currentPage,
                'last_page' => $result->lastPage,
            ],
        ]);
    }

    public function show(ShowEventLogRequest $request, int $id): JsonResponse
    {
        $eventLog = $this->eventLogs->find($id);

        if ($eventLog === null) {
            return response()->json(['message' => 'Event log not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => (new EventLogResource($eventLog))->resolve(),
        ]);
    }
}
