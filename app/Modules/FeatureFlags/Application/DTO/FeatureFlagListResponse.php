<?php

namespace App\Modules\FeatureFlags\Application\DTO;

final class FeatureFlagListResponse
{
    /**
     * @param FeatureFlagResponse[] $items
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
