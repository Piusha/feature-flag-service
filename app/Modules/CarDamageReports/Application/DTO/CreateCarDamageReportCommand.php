<?php

namespace App\Modules\CarDamageReports\Application\DTO;

use App\Modules\CarDamageReports\Domain\Enums\ReportSeverity;
use App\Modules\CarDamageReports\Domain\Enums\ReportStatus;

final class CreateCarDamageReportCommand
{
    public function __construct(
        public readonly string $customerName,
        public readonly string $vehicleRegistration,
        public readonly string $vehicleModel,
        public readonly string $damageDescription,
        public readonly ReportSeverity $severity,
        public readonly ?float $repairEstimateAmount,
        public readonly ReportStatus $status,
        public readonly \DateTimeImmutable $incidentDate,
        public readonly ?string $incidentLocation,
    ) {
    }
}
