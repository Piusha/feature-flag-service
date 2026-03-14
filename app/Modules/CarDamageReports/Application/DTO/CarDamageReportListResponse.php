<?php

namespace App\Modules\CarDamageReports\Application\DTO;

final class CarDamageReportListResponse
{
    /**
     * @param CarDamageReportResponse[] $items
     */
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $perPage,
        public readonly int $currentPage,
        public readonly int $lastPage,
    ) {
    }
}
