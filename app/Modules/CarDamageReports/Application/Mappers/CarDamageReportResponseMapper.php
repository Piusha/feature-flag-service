<?php

namespace App\Modules\CarDamageReports\Application\Mappers;

use App\Modules\CarDamageReports\Application\DTO\CarDamageReportResponse;
use App\Modules\CarDamageReports\Application\DTO\ReportHistoryResponse;
use App\Modules\CarDamageReports\Application\DTO\ReportPhotoResponse;
use App\Modules\CarDamageReports\Domain\Entities\CarDamageReportEntity;
use App\Modules\CarDamageReports\Domain\Entities\ReportHistoryEntity;
use App\Modules\CarDamageReports\Domain\Entities\ReportPhotoEntity;

final class CarDamageReportResponseMapper
{
    public function toResponse(CarDamageReportEntity $entity): CarDamageReportResponse
    {
        return new CarDamageReportResponse(
            id: $entity->id() ?? 0,
            referenceNumber: $entity->referenceNumber()->value(),
            customerName: $entity->customerName(),
            vehicleRegistration: $entity->vehicleRegistration(),
            vehicleModel: $entity->vehicleModel(),
            damageDescription: $entity->damageDescription(),
            severity: $entity->severity()->value,
            repairEstimateAmount: $entity->repairEstimateAmount(),
            status: $entity->status()->value,
            incidentDate: $entity->incidentDate()->format('Y-m-d'),
            incidentLocation: $entity->incidentLocation(),
            createdAt: $entity->createdAt()?->format(DATE_ATOM),
            updatedAt: $entity->updatedAt()?->format(DATE_ATOM),
            photos: array_map([$this, 'mapPhoto'], $entity->photos()),
            history: array_map([$this, 'mapHistory'], $entity->history()),
        );
    }

    public function mapPhoto(ReportPhotoEntity $photo): ReportPhotoResponse
    {
        return new ReportPhotoResponse(
            id: $photo->id() ?? 0,
            reportId: $photo->reportId(),
            fileName: $photo->fileName(),
            filePath: $photo->filePath(),
            mimeType: $photo->mimeType(),
            size: $photo->size(),
            createdAt: $photo->createdAt()?->format(DATE_ATOM),
            updatedAt: $photo->updatedAt()?->format(DATE_ATOM),
        );
    }

    public function mapHistory(ReportHistoryEntity $history): ReportHistoryResponse
    {
        return new ReportHistoryResponse(
            id: $history->id() ?? 0,
            reportId: $history->reportId(),
            eventType: $history->eventType(),
            description: $history->description(),
            createdAt: $history->createdAt()?->format(DATE_ATOM),
        );
    }
}
