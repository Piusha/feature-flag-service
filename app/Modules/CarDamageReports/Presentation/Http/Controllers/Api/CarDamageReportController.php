<?php

namespace App\Modules\CarDamageReports\Presentation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\CarDamageReports\Application\Contracts\ManageCarDamageReportsUseCaseInterface;
use App\Modules\CarDamageReports\Presentation\Http\Resources\CarDamageReportResource;
use App\Modules\CarDamageReports\Presentation\Http\Resources\ReportHistoryResource;
use App\Modules\CarDamageReports\Presentation\Http\Resources\ReportPhotoResource;
use App\Modules\CarDamageReports\Presentation\Http\Requests\StoreCarDamageReportRequest;
use App\Modules\CarDamageReports\Presentation\Http\Requests\UpdateCarDamageReportRequest;
use App\Modules\CarDamageReports\Presentation\Http\Requests\UploadReportPhotoRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CarDamageReportController extends Controller
{
    public function __construct(private readonly ManageCarDamageReportsUseCaseInterface $reports) {}

    public function index(): JsonResponse
    {
        $page = (int) request()->query('page', 1);
        $result = $this->reports->listPaginated(page: $page);
        $data = array_map(
            fn($item): array => (new CarDamageReportResource($item))->resolve(),
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

    public function store(StoreCarDamageReportRequest $request): JsonResponse
    {

        $report = $this->reports->create($request->toCommand());

        return response()->json((new CarDamageReportResource($report))->resolve(), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $report = $this->reports->find($id);

        if ($report === null) {
            return response()->json(['message' => 'Report not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json((new CarDamageReportResource($report))->resolve());
    }

    public function update(UpdateCarDamageReportRequest $request, int $id): JsonResponse
    {
        $updated = $this->reports->update($request->toCommand());

        if ($updated === null) {
            return response()->json(['message' => 'Report not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json((new CarDamageReportResource($updated))->resolve());
    }

    public function uploadPhoto(UploadReportPhotoRequest $request, int $id): JsonResponse
    {
        $photo = $this->reports->addPhoto($request->toCommand($id));

        if ($photo === null) {
            return response()->json(['message' => 'Report not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json((new ReportPhotoResource($photo))->resolve(), Response::HTTP_CREATED);
    }

    public function history(int $id): JsonResponse
    {
        $history = $this->reports->history($id);

        if ($history === null) {
            return response()->json(['message' => 'Report not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(
            array_map(fn($item): array => (new ReportHistoryResource($item))->resolve(), $history)
        );
    }
}
