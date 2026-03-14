<?php

namespace App\Modules\CarDamageReports\Application\UseCases;

use App\Modules\CarDamageReports\Application\DTO\CarDamageReportListResponse;
use App\Modules\CarDamageReports\Application\DTO\CarDamageReportResponse;
use App\Modules\CarDamageReports\Application\DTO\CreateCarDamageReportCommand;
use App\Modules\CarDamageReports\Application\DTO\ReportHistoryResponse;
use App\Modules\CarDamageReports\Application\DTO\ReportPhotoResponse;
use App\Modules\CarDamageReports\Application\DTO\UpdateCarDamageReportCommand;
use App\Modules\CarDamageReports\Application\DTO\UploadReportPhotoCommand;
use App\Modules\CarDamageReports\Application\Mappers\CarDamageReportResponseMapper;
use App\Modules\CarDamageReports\Application\Contracts\ManageCarDamageReportsUseCaseInterface;
use App\Modules\CarDamageReports\Domain\Entities\CarDamageReportEntity;
use App\Modules\CarDamageReports\Domain\Entities\ReportPhotoEntity;
use App\Modules\CarDamageReports\Domain\Repositories\CarDamageReportRepository;
use App\Modules\CarDamageReports\Domain\ValueObjects\ReportReferenceNumber;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ManageCarDamageReportsUseCase implements ManageCarDamageReportsUseCaseInterface
{
    public function __construct(
        private readonly CarDamageReportRepository $reports,
        private readonly CarDamageReportResponseMapper $mapper,
    ) {}

    public function listPaginated(int $perPage = 15, int $page = 1): CarDamageReportListResponse
    {
        $paginated = $this->reports->paginate($perPage, $page);
        $items = array_map(fn($entity): CarDamageReportResponse => $this->mapper->toResponse($entity), $paginated->items());

        return new CarDamageReportListResponse(
            items: $items,
            total: $paginated->total(),
            perPage: $paginated->perPage(),
            currentPage: $paginated->currentPage(),
            lastPage: $paginated->lastPage(),
        );
    }

    public function find(int $id): ?CarDamageReportResponse
    {
        $report = $this->reports->findById($id);

        return $report ? $this->mapper->toResponse($report) : null;
    }

    public function create(CreateCarDamageReportCommand $command): CarDamageReportResponse
    {
        Log::info('Creating car damage report', [
            'command' => $command,
        ]);
        $report = $this->reports->create(new CarDamageReportEntity(
            id: null,
            referenceNumber: new ReportReferenceNumber($this->generateReferenceNumber()),
            customerName: $command->customerName,
            vehicleRegistration: $command->vehicleRegistration,
            vehicleModel: $command->vehicleModel,
            damageDescription: $command->damageDescription,
            severity: $command->severity,
            repairEstimateAmount: $command->repairEstimateAmount,
            status: $command->status,
            incidentDate: $command->incidentDate,
            incidentLocation: $command->incidentLocation,
        ));

        $this->reports->appendHistory($report->id() ?? 0, 'report_created', 'Report was created.');

        $fresh = $this->reports->findById($report->id() ?? 0);

        return $this->mapper->toResponse($fresh ?? $report);
    }

    public function update(UpdateCarDamageReportCommand $command): ?CarDamageReportResponse
    {
        $existing = $this->reports->findById($command->reportId);

        if ($existing === null) {
            return null;
        }

        Log::info('Updating car damage report', [
            'command' => $command,
            'existing' => $existing,
        ]);

        $updated = $this->reports->update(new CarDamageReportEntity(
            id: $existing->id(),
            referenceNumber: $existing->referenceNumber(),
            customerName: $command->hasCustomerName ? (string) $command->customerName : $existing->customerName(),
            vehicleRegistration: $command->hasVehicleRegistration ? (string) $command->vehicleRegistration : $existing->vehicleRegistration(),
            vehicleModel: $command->hasVehicleModel ? (string) $command->vehicleModel : $existing->vehicleModel(),
            damageDescription: $command->hasDamageDescription ? (string) $command->damageDescription : $existing->damageDescription(),
            severity: $command->hasSeverity ? ($command->severity ?? $existing->severity()) : $existing->severity(),
            repairEstimateAmount: $command->hasRepairEstimateAmount ? $command->repairEstimateAmount : $existing->repairEstimateAmount(),
            status: $command->hasStatus ? ($command->status ?? $existing->status()) : $existing->status(),
            incidentDate: $command->hasIncidentDate ? ($command->incidentDate ?? $existing->incidentDate()) : $existing->incidentDate(),
            incidentLocation: $command->hasIncidentLocation ? $command->incidentLocation : $existing->incidentLocation(),
            createdAt: $existing->createdAt(),
            updatedAt: $existing->updatedAt(),
            photos: $existing->photos(),
            history: $existing->history(),
        ));

        Log::info('Car damage report updated', [
            'updated' => $updated,
        ]);

        $this->reports->appendHistory($updated->id() ?? 0, 'report_updated', 'Report was updated.');
        $fresh = $this->reports->findById($updated->id() ?? 0);

        return $fresh ? $this->mapper->toResponse($fresh) : $this->mapper->toResponse($updated);
    }

    public function addPhoto(UploadReportPhotoCommand $command): ?ReportPhotoResponse
    {
        Log::info('Adding photo to car damage report', [
            'command' => $command,
        ]);
        $report = $this->reports->findById($command->reportId);

        if ($report === null) {
            return null;
        }

        $storedPath = $command->photo->store('reports/photos', 'public');
        $photo = $this->reports->addPhoto(
            $command->reportId,
            new ReportPhotoEntity(
                id: null,
                reportId: $command->reportId,
                fileName: $command->photo->getClientOriginalName(),
                filePath: $storedPath,
                mimeType: $command->photo->getClientMimeType() ?? 'application/octet-stream',
                size: $command->photo->getSize(),
            )
        );

        Log::info('Photo added to car damage report', [
            'photo' => $photo,
        ]);

        $this->reports->appendHistory($command->reportId, 'photo_uploaded', 'A report photo was uploaded.');

        return $this->mapper->mapPhoto($photo);
    }

    public function history(int $reportId): ?array
    {
        Log::info('Getting history for car damage report', [
            'reportId' => $reportId,
        ]);
        $report = $this->reports->findById($reportId);

        if ($report === null) {
            return null;
        }

        $history = $this->reports->historyForReport($reportId);

        Log::info('History for car damage report', [
            'history' => $history,
        ]);

        return array_map(
            fn($item): ReportHistoryResponse => $this->mapper->mapHistory($item),
            $history
        );
    }

    private function generateReferenceNumber(): string
    {
        return 'CDR-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
    }
}
