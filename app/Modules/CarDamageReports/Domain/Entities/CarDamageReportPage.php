<?php

namespace App\Modules\CarDamageReports\Domain\Entities;

final class CarDamageReportPage
{
    /**
     * @param CarDamageReportEntity[] $items
     */
    public function __construct(
        private readonly array $items,
        private readonly int $total,
        private readonly int $perPage,
        private readonly int $currentPage,
        private readonly int $lastPage,
    ) {
    }

    public function items(): array { return $this->items; }
    public function total(): int { return $this->total; }
    public function perPage(): int { return $this->perPage; }
    public function currentPage(): int { return $this->currentPage; }
    public function lastPage(): int { return $this->lastPage; }
}
