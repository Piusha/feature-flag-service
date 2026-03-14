<?php

namespace App\Modules\FeatureFlags\Domain\Repositories;

use App\Modules\FeatureFlags\Domain\Entities\FeatureFlagEntity;
use App\Modules\FeatureFlags\Domain\Entities\FeatureFlagPage;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;

interface FeatureFlagRepository
{
    public function paginate(int $perPage = 15, int $page = 1): FeatureFlagPage;

    /**
     * @return FeatureFlagEntity[]
     */
    public function all(): array;

    public function findById(int $id): ?FeatureFlagEntity;

    public function findByKey(FeatureFlagKey $key): ?FeatureFlagEntity;

    public function create(FeatureFlagEntity $featureFlag): FeatureFlagEntity;

    public function update(FeatureFlagEntity $featureFlag): FeatureFlagEntity;

    public function deleteById(int $id): void;
}
