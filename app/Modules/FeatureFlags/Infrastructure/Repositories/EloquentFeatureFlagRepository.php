<?php

namespace App\Modules\FeatureFlags\Infrastructure\Repositories;

use App\Modules\FeatureFlags\Domain\Entities\FeatureFlagEntity;
use App\Modules\FeatureFlags\Domain\Entities\FeatureFlagPage;
use App\Modules\FeatureFlags\Domain\Repositories\FeatureFlagRepository;
use App\Modules\FeatureFlags\Domain\ValueObjects\FeatureFlagKey;
use App\Modules\FeatureFlags\Infrastructure\Mappers\FeatureFlagMapper;
use App\Modules\FeatureFlags\Infrastructure\Models\FeatureFlag;
use Illuminate\Support\Facades\DB;

class EloquentFeatureFlagRepository implements FeatureFlagRepository
{
    public function __construct(private readonly FeatureFlagMapper $mapper) {}

    public function paginate(int $perPage = 15, int $page = 1): FeatureFlagPage
    {
        $paginator = FeatureFlag::query()->orderBy('id')->paginate($perPage, ['*'], 'page', $page);
        $items = [];

        foreach ($paginator->items() as $item) {
            /** @var FeatureFlag $item */
            $items[] = $this->mapper->toDomain($item);
        }

        return new FeatureFlagPage(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage(),
        );
    }

    public function all(): array
    {
        return FeatureFlag::query()
            ->orderBy('id')
            ->get()
            ->map(fn(FeatureFlag $featureFlag): FeatureFlagEntity => $this->mapper->toDomain($featureFlag))
            ->all();
    }

    public function findById(int $id): ?FeatureFlagEntity
    {
        $featureFlag = FeatureFlag::query()->find($id);


        return $featureFlag ? $this->mapper->toDomain($featureFlag) : null;
    }

    public function findByKey(FeatureFlagKey $key): ?FeatureFlagEntity
    {
        $featureFlag = FeatureFlag::query()->where('key', $key->value())->first();

        return $featureFlag ? $this->mapper->toDomain($featureFlag) : null;
    }

    public function create(FeatureFlagEntity $featureFlag): FeatureFlagEntity
    {
        return DB::transaction(function () use ($featureFlag): FeatureFlagEntity {
            /** @var FeatureFlag $created */
            $created = FeatureFlag::query()->create($this->mapper->toPersistence($featureFlag));

            return $this->mapper->toDomain($created);
        });
    }

    public function update(FeatureFlagEntity $featureFlag): FeatureFlagEntity
    {

        return DB::transaction(function () use ($featureFlag): FeatureFlagEntity {
            $model = FeatureFlag::query()->findOrFail($featureFlag->id());
            $model->fill($this->mapper->toPersistence($featureFlag));
            $model->save();

            return $this->mapper->toDomain($model);
        });
    }

    public function deleteById(int $id): void
    {
        DB::transaction(function () use ($id): void {
            FeatureFlag::query()->whereKey($id)->delete();
        });
    }
}
