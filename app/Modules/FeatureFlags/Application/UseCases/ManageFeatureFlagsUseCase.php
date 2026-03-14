<?php

namespace App\Modules\FeatureFlags\Application\UseCases;

use App\Modules\FeatureFlags\Application\DTO\CreateFeatureFlagCommand;
use App\Modules\FeatureFlags\Application\DTO\FeatureFlagListResponse;
use App\Modules\FeatureFlags\Application\DTO\FeatureFlagResponse;
use App\Modules\FeatureFlags\Application\DTO\UpdateFeatureFlagCommand;
use App\Modules\FeatureFlags\Application\Contracts\ActorContextProviderInterface;
use App\Modules\FeatureFlags\Application\Contracts\FeatureFlagCacheInterface;
use App\Modules\FeatureFlags\Application\Contracts\ManageFeatureFlagsUseCaseInterface;
use App\Modules\FeatureFlags\Application\Mappers\FeatureFlagResponseMapper;
use App\Modules\FeatureFlags\Domain\Entities\FeatureFlagEntity;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagCreated;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagDeleted;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagUpdated;
use App\Modules\FeatureFlags\Domain\Repositories\FeatureFlagRepository;
use App\Modules\FeatureFlags\Domain\ValueObjects\FlagSchedule;
use App\SharedKernel\Domain\Clock;
use RuntimeException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class ManageFeatureFlagsUseCase implements ManageFeatureFlagsUseCaseInterface
{
    public function __construct(
        private readonly FeatureFlagRepository $featureFlags,
        private readonly FeatureFlagCacheInterface $cache,
        private readonly FeatureFlagResponseMapper $mapper,
        private readonly ActorContextProviderInterface $actorContextProvider,
        private readonly Clock $clock,
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
        Log::info('Creating feature flag', [
            'command' => $command,
        ]);
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

        $actor = $this->actorContextProvider->resolve();
        $aggregateId = $featureFlag->id();

        if ($aggregateId === null) {
            Log::error('Cannot dispatch creation event for feature flag without aggregate id.', [
                'command' => $command,
            ]);
            throw new RuntimeException('Cannot dispatch creation event for feature flag without aggregate id.');
        }

        Event::dispatch(new FeatureFlagCreated(
            aggregateId: $aggregateId,
            featureFlagKey: $featureFlag->key()->value(),
            actorId: $actor->actorId,
            actorType: $actor->actorType,
            context: [
                'module' => 'feature_flags',
                'operation' => 'create',
            ],
            occurredAt: $this->clock->now(),
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

        Log::info('Updating feature flag', [
            'command' => $command,
            'existing' => $existing,
        ]);

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

        $actor = $this->actorContextProvider->resolve();
        $aggregateId = $updated->id();

        if ($aggregateId === null) {
            Log::error('Cannot dispatch update event for feature flag without aggregate id.', [
                'command' => $command,
                'existing' => $existing,
            ]);
            throw new RuntimeException('Cannot dispatch update event for feature flag without aggregate id.');
        }

        Event::dispatch(new FeatureFlagUpdated(
            aggregateId: $aggregateId,
            featureFlagKey: $updated->key()->value(),
            actorId: $actor->actorId,
            actorType: $actor->actorType,
            changes: $this->buildChanges($existing, $updated),
            context: [
                'module' => 'feature_flags',
                'operation' => 'update',
            ],
            occurredAt: $this->clock->now(),
        ));

        $this->cache->invalidateAllContexts();

        return $this->mapper->toResponse($updated);
    }

    public function delete(int $id): void
    {
        $existing = $this->featureFlags->findById($id);

        if ($existing === null) {
            Log::debug('Cannot dispatch delete event for feature flag without existing feature flag.', [
                'id' => $id,
            ]);
            return;
        }

        $this->featureFlags->deleteById($id);

        $actor = $this->actorContextProvider->resolve();

        Event::dispatch(new FeatureFlagDeleted(
            aggregateId: $id,
            featureFlagKey: $existing->key()->value(),
            actorId: $actor->actorId,
            actorType: $actor->actorType,
            context: [
                'module' => 'feature_flags',
                'operation' => 'delete',
            ],
            occurredAt: $this->clock->now(),
        ));

        $this->cache->invalidateAllContexts();
    }

    private function buildChanges(FeatureFlagEntity $existing, FeatureFlagEntity $updated): array
    {
        $before = $this->toComparableState($existing);
        $after = $this->toComparableState($updated);
        $changes = [];

        foreach ($after as $field => $newValue) {
            $oldValue = $before[$field] ?? null;

            if ($oldValue !== $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    private function toComparableState(FeatureFlagEntity $entity): array
    {
        return [
            'key' => $entity->key()->value(),
            'name' => $entity->name(),
            'description' => $entity->description(),
            'type' => $entity->type()->value,
            'scope' => $entity->scope()->value,
            'enabled' => $entity->enabled(),
            'rollout_percentage' => $entity->rolloutPercentage()?->value(),
            'starts_at' => $entity->schedule()->startsAt()?->format(DATE_ATOM),
            'expires_at' => $entity->schedule()->expiresAt()?->format(DATE_ATOM),
        ];
    }
}
