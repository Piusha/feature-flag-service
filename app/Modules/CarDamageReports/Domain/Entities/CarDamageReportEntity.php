<?php

namespace App\Modules\CarDamageReports\Domain\Entities;

use App\Modules\CarDamageReports\Domain\Enums\ReportSeverity;
use App\Modules\CarDamageReports\Domain\Enums\ReportStatus;
use App\Modules\CarDamageReports\Domain\ValueObjects\ReportReferenceNumber;

final class CarDamageReportEntity
{
    /**
     * @param ReportPhotoEntity[] $photos
     * @param ReportHistoryEntity[] $history
     */
    public function __construct(
        private readonly ?int $id,
        private readonly ReportReferenceNumber $referenceNumber,
        private readonly string $customerName,
        private readonly string $vehicleRegistration,
        private readonly string $vehicleModel,
        private readonly string $damageDescription,
        private readonly ReportSeverity $severity,
        private readonly ?float $repairEstimateAmount,
        private readonly ReportStatus $status,
        private readonly \DateTimeImmutable $incidentDate,
        private readonly ?string $incidentLocation,
        private readonly ?\DateTimeImmutable $createdAt = null,
        private readonly ?\DateTimeImmutable $updatedAt = null,
        private readonly array $photos = [],
        private readonly array $history = [],
    ) {
    }

    public function id(): ?int { return $this->id; }
    public function referenceNumber(): ReportReferenceNumber { return $this->referenceNumber; }
    public function customerName(): string { return $this->customerName; }
    public function vehicleRegistration(): string { return $this->vehicleRegistration; }
    public function vehicleModel(): string { return $this->vehicleModel; }
    public function damageDescription(): string { return $this->damageDescription; }
    public function severity(): ReportSeverity { return $this->severity; }
    public function repairEstimateAmount(): ?float { return $this->repairEstimateAmount; }
    public function status(): ReportStatus { return $this->status; }
    public function incidentDate(): \DateTimeImmutable { return $this->incidentDate; }
    public function incidentLocation(): ?string { return $this->incidentLocation; }
    public function createdAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function updatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function photos(): array { return $this->photos; }
    public function history(): array { return $this->history; }
}
