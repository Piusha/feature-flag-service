<?php

namespace App\Modules\FeatureFlags\Application\Contracts;

use App\Modules\FeatureFlags\Application\DTO\CreateFeatureFlagCommand;
use App\Modules\FeatureFlags\Application\DTO\FeatureFlagListResponse;
use App\Modules\FeatureFlags\Application\DTO\FeatureFlagResponse;
use App\Modules\FeatureFlags\Application\DTO\UpdateFeatureFlagCommand;

interface ManageFeatureFlagsUseCaseInterface
{
    public function listPaginated(int $perPage = 15, int $page = 1): FeatureFlagListResponse;

    public function find(int $id): ?FeatureFlagResponse;

    public function create(CreateFeatureFlagCommand $command): FeatureFlagResponse;

    public function update(UpdateFeatureFlagCommand $command): ?FeatureFlagResponse;

    public function delete(int $id): void;
}
