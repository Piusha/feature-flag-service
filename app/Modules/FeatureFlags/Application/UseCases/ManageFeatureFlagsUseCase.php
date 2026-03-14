<?php

namespace App\Modules\FeatureFlags\Application\UseCases;

use App\Modules\FeatureFlags\Application\DTO\CreateFeatureFlagCommand;
use App\Modules\FeatureFlags\Application\DTO\FeatureFlagListResponse;
use App\Modules\FeatureFlags\Application\DTO\FeatureFlagResponse;
use App\Modules\FeatureFlags\Application\DTO\UpdateFeatureFlagCommand;
use App\Modules\FeatureFlags\Application\Contracts\FeatureFlagCacheInterface;
use App\Modules\FeatureFlags\Application\Contracts\ManageFeatureFlagsUseCaseInterface;
use App\Modules\FeatureFlags\Application\Mappers\FeatureFlagResponseMapper;
use App\Modules\FeatureFlags\Domain\Entities\FeatureFlagEntity;
use App\Modules\FeatureFlags\Domain\Repositories\FeatureFlagRepository;
use App\Modules\FeatureFlags\Domain\ValueObjects\FlagSchedule;

class ManageFeatureFlagsUseCase implements ManageFeatureFlagsUseCaseInterface
{
    public function __construct(
        private readonly FeatureFlagRepository $featureFlags,
        private readonly FeatureFlagCacheInterface $cache,
        private readonly FeatureFlagResponseMapper $mapper,
    ) {}

    public function listPaginated(int $perPage = 15, int $page = 1): FeatureFlagListResponse
    {
        $paginated = $this->featureFlags->paginate($perPage, $page);
        $items = array_map(
            fn(FeatureFlagEntity $entity): FeatureFlagResponse => $this->mapper->toResponse($entity),
            $paginated->items()
        );

        return new FeatureFlagListResponse(
            items: $items,
            total: $paginated->total(),
            perPage: $paginated->perPage(),
            currentPage: $paginated->currentPage(),
            lastPage: $paginated->lastPage(),
        );
    }

    public function find(int $id): ?FeatureFlagResponse
    {
        $entity = $this->featureFlags->findById($id);

        return $entity ? $this->mapper->toResponse($entity) : null;
    }

    public function create(CreateFeatureFlagCommand $command): FeatureFlagResponse
    {
        $featureFlag = $this->featureFlags->create(new FeatureFlagEntity(
            id: null,
            key: $command->key,
            name: $command->name,
            description: $command->description,
            type: $command->type,
            scope: $command->scope,
            enabled: $command->enabled,
            rolloutPercentage: $command->rolloutPercentage,
            schedule: $command->schedule,
        ));

        $this->cache->invalidateAllContexts();

        return $this->mapper->toResponse($featureFlag);
    }

    public function update(UpdateFeatureFlagCommand $command): ?FeatureFlagResponse
    {
        $existing = $this->featureFlags->findById($command->id);

        if ($existing === null) {
            return null;
        }

        $schedule = new FlagSchedule(
            startsAt: $command->hasStartsAt ? $command->startsAt : $existing->schedule()->startsAt(),
            expiresAt: $command->hasExpiresAt ? $command->expiresAt : $existing->schedule()->expiresAt(),
        );

        $updated = $this->featureFlags->update(new FeatureFlagEntity(
            id: $command->id,
            key: $command->key,
            name: $command->name,
            description: $command->hasDescription ? $command->description : $existing->description(),
            type: $command->type,
            scope: $command->scope,
            enabled: $command->enabled,
            rolloutPercentage: $command->hasRolloutPercentage ? $command->rolloutPercentage : $existing->rolloutPercentage(),
            schedule: $schedule,
        ));

        $this->cache->invalidateAllContexts();

        return $this->mapper->toResponse($updated);
    }

    public function delete(int $id): void
    {
        $this->featureFlags->deleteById($id);
        $this->cache->invalidateAllContexts();
    }
}
