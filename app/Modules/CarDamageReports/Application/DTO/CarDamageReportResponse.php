<?php

namespace App\Modules\CarDamageReports\Application\DTO;

final class CarDamageReportResponse
{
    /**
     * @param ReportPhotoResponse[] $photos
     * @param ReportHistoryResponse[] $history
     */
    public function __construct(
        public readonly int $id,
        public readonly string $referenceNumber,
        public readonly string $customerName,
        public readonly string $vehicleRegistration,
        public readonly string $vehicleModel,
        public readonly string $damageDescription,
        public readonly string $severity,
        public readonly ?float $repairEstimateAmount,
        public readonly string $status,
        public readonly string $incidentDate,
        public readonly ?string $incidentLocation,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
        public readonly array $photos,
        public readonly array $history,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'reference_number' => $this->referenceNumber,
            'customer_name' => $this->customerName,
            'vehicle_registration' => $this->vehicleRegistration,
            'vehicle_model' => $this->vehicleModel,
            'damage_description' => $this->damageDescription,
            'severity' => $this->severity,
            'repair_estimate_amount' => $this->repairEstimateAmount,
            'status' => $this->status,
            'incident_date' => $this->incidentDate,
            'incident_location' => $this->incidentLocation,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'photos' => array_map(fn (ReportPhotoResponse $photo): array => $photo->toArray(), $this->photos),
            'history' => array_map(fn (ReportHistoryResponse $item): array => $item->toArray(), $this->history),
        ];
    }
}
