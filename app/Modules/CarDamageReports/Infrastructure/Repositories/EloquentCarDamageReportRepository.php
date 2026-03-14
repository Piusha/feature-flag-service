<?php

namespace App\Modules\CarDamageReports\Infrastructure\Repositories;

use App\Modules\CarDamageReports\Domain\Entities\CarDamageReportEntity;
use App\Modules\CarDamageReports\Domain\Entities\CarDamageReportPage;
use App\Modules\CarDamageReports\Domain\Entities\ReportHistoryEntity;
use App\Modules\CarDamageReports\Domain\Entities\ReportPhotoEntity;
use App\Modules\CarDamageReports\Domain\Repositories\CarDamageReportRepository;
use App\Modules\CarDamageReports\Infrastructure\Mappers\CarDamageReportMapper;
use App\Modules\CarDamageReports\Infrastructure\Models\CarDamageReport;

class EloquentCarDamageReportRepository implements CarDamageReportRepository
{
    public function __construct(private readonly CarDamageReportMapper $mapper)
    {
    }

    public function paginate(int $perPage = 15, int $page = 1): CarDamageReportPage
    {
        $paginator = CarDamageReport::query()->with(['photos', 'history'])->latest('id')->paginate($perPage, ['*'], 'page', $page);
        $items = [];
        foreach ($paginator->items() as $item) {
            /** @var CarDamageReport $item */
            $items[] = $this->mapper->toDomain($item);
        }

        return new CarDamageReportPage(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage(),
        );
    }

    public function findById(int $id): ?CarDamageReportEntity
    {
        $report = CarDamageReport::query()->with(['photos', 'history'])->find($id);

        return $report ? $this->mapper->toDomain($report) : null;
    }

    public function create(CarDamageReportEntity $report): CarDamageReportEntity
    {
        /** @var CarDamageReport $created */
        $created = CarDamageReport::query()->create($this->mapper->toPersistence($report));

        return $this->mapper->toDomain($created->load(['photos', 'history']));
    }

    public function update(CarDamageReportEntity $report): CarDamageReportEntity
    {
        $model = CarDamageReport::query()->findOrFail($report->id());
        $model->fill($this->mapper->toPersistence($report));
        $model->save();

        return $this->mapper->toDomain($model->load(['photos', 'history']));
    }

    public function addPhoto(int $reportId, ReportPhotoEntity $photo): ReportPhotoEntity
    {
        $model = CarDamageReport::query()->findOrFail($reportId);
        $created = $model->photos()->create([
            'file_name' => $photo->fileName(),
            'file_path' => $photo->filePath(),
            'mime_type' => $photo->mimeType(),
            'size' => $photo->size(),
        ]);

        return $this->mapper->mapPhoto($created);
    }

    public function appendHistory(int $reportId, string $eventType, string $description): ReportHistoryEntity
    {
        $model = CarDamageReport::query()->findOrFail($reportId);
        $history = $model->history()->create([
            'event_type' => $eventType,
            'description' => $description,
        ]);

        return $this->mapper->mapHistory($history);
    }

    public function historyForReport(int $reportId): array
    {
        $model = CarDamageReport::query()->findOrFail($reportId);

        return $model->history()->get()
            ->map(fn ($history): ReportHistoryEntity => $this->mapper->mapHistory($history))
            ->all();
    }
}
