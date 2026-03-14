<?php

namespace App\Modules\CarDamageReports\Application\DTO;

use App\Modules\CarDamageReports\Domain\Enums\ReportSeverity;
use App\Modules\CarDamageReports\Domain\Enums\ReportStatus;

final class UpdateCarDamageReportCommand
{
    public function __construct(
        public readonly int $reportId,
        public readonly ?string $customerName,
        public readonly bool $hasCustomerName,
        public readonly ?string $vehicleRegistration,
        public readonly bool $hasVehicleRegistration,
        public readonly ?string $vehicleModel,
        public readonly bool $hasVehicleModel,
        public readonly ?string $damageDescription,
        public readonly bool $hasDamageDescription,
        public readonly ?ReportSeverity $severity,
        public readonly bool $hasSeverity,
        public readonly ?float $repairEstimateAmount,
        public readonly bool $hasRepairEstimateAmount,
        public readonly ?ReportStatus $status,
        public readonly bool $hasStatus,
        public readonly ?\DateTimeImmutable $incidentDate,
        public readonly bool $hasIncidentDate,
        public readonly ?string $incidentLocation,
        public readonly bool $hasIncidentLocation,
    ) {
    }
}
