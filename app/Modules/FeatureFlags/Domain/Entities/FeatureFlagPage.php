<?php

namespace App\Modules\FeatureFlags\Domain\Entities;

final class FeatureFlagPage
{
    /**
     * @param FeatureFlagEntity[] $items
     */
    public function __construct(
        private readonly array $items,
        private readonly int $total,
        private readonly int $perPage,
        private readonly int $currentPage,
        private readonly int $lastPage,
    ) {
    }

    /**
     * @return FeatureFlagEntity[]
     */
    public function items(): array
    {
        return $this->items;
    }

    public function total(): int { return $this->total; }
    public function perPage(): int { return $this->perPage; }
    public function currentPage(): int { return $this->currentPage; }
    public function lastPage(): int { return $this->lastPage; }
}
