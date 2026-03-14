<?php

namespace App\Modules\CarDamageReports\Infrastructure\Mappers;

use App\Modules\CarDamageReports\Domain\Entities\CarDamageReportEntity;
use App\Modules\CarDamageReports\Domain\Entities\ReportHistoryEntity;
use App\Modules\CarDamageReports\Domain\Entities\ReportPhotoEntity;
use App\Modules\CarDamageReports\Domain\Enums\ReportSeverity;
use App\Modules\CarDamageReports\Domain\Enums\ReportStatus;
use App\Modules\CarDamageReports\Domain\ValueObjects\ReportReferenceNumber;
use App\Modules\CarDamageReports\Infrastructure\Models\CarDamageReport;
use App\Modules\CarDamageReports\Infrastructure\Models\ReportHistory;
use App\Modules\CarDamageReports\Infrastructure\Models\ReportPhoto;

final class CarDamageReportMapper
{
    public function toDomain(CarDamageReport $model): CarDamageReportEntity
    {
        $photos = $model->relationLoaded('photos')
            ? $model->photos->map(fn (ReportPhoto $photo): ReportPhotoEntity => $this->mapPhoto($photo))->all()
            : [];
        $history = $model->relationLoaded('history')
            ? $model->history->map(fn (ReportHistory $item): ReportHistoryEntity => $this->mapHistory($item))->all()
            : [];

        return new CarDamageReportEntity(
            id: $model->id,
            referenceNumber: new ReportReferenceNumber($model->reference_number),
            customerName: $model->customer_name,
            vehicleRegistration: $model->vehicle_registration,
            vehicleModel: $model->vehicle_model,
            damageDescription: $model->damage_description,
            severity: ReportSeverity::from($model->severity),
            repairEstimateAmount: $model->repair_estimate_amount !== null ? (float) $model->repair_estimate_amount : null,
            status: ReportStatus::from($model->status),
            incidentDate: new \DateTimeImmutable($model->incident_date->format('Y-m-d')),
            incidentLocation: $model->incident_location,
            createdAt: $model->created_at?->toDateTimeImmutable(),
            updatedAt: $model->updated_at?->toDateTimeImmutable(),
            photos: $photos,
            history: $history,
        );
    }

    public function toPersistence(CarDamageReportEntity $entity): array
    {
        return [
            'reference_number' => $entity->referenceNumber()->value(),
            'customer_name' => $entity->customerName(),
            'vehicle_registration' => $entity->vehicleRegistration(),
            'vehicle_model' => $entity->vehicleModel(),
            'damage_description' => $entity->damageDescription(),
            'severity' => $entity->severity()->value,
            'repair_estimate_amount' => $entity->repairEstimateAmount(),
            'status' => $entity->status()->value,
            'incident_date' => $entity->incidentDate()->format('Y-m-d'),
            'incident_location' => $entity->incidentLocation(),
        ];
    }

    public function mapPhoto(ReportPhoto $photo): ReportPhotoEntity
    {
        return new ReportPhotoEntity(
            id: $photo->id,
            reportId: $photo->report_id,
            fileName: $photo->file_name,
            filePath: $photo->file_path,
            mimeType: $photo->mime_type,
            size: (int) $photo->size,
            createdAt: $photo->created_at?->toDateTimeImmutable(),
            updatedAt: $photo->updated_at?->toDateTimeImmutable(),
        );
    }

    public function mapHistory(ReportHistory $history): ReportHistoryEntity
    {
        return new ReportHistoryEntity(
            id: $history->id,
            reportId: $history->report_id,
            eventType: $history->event_type,
            description: $history->description,
            createdAt: $history->created_at?->toDateTimeImmutable(),
        );
    }
}
