<?php

namespace App\Modules\CarDamageReports\Domain\Repositories;

use App\Modules\CarDamageReports\Domain\Entities\CarDamageReportEntity;
use App\Modules\CarDamageReports\Domain\Entities\CarDamageReportPage;
use App\Modules\CarDamageReports\Domain\Entities\ReportHistoryEntity;
use App\Modules\CarDamageReports\Domain\Entities\ReportPhotoEntity;

interface CarDamageReportRepository
{
    public function paginate(int $perPage = 15, int $page = 1): CarDamageReportPage;

    public function findById(int $id): ?CarDamageReportEntity;

    public function create(CarDamageReportEntity $report): CarDamageReportEntity;

    public function update(CarDamageReportEntity $report): CarDamageReportEntity;

    public function addPhoto(int $reportId, ReportPhotoEntity $photo): ReportPhotoEntity;

    public function appendHistory(int $reportId, string $eventType, string $description): ReportHistoryEntity;

    /**
     * @return ReportHistoryEntity[]
     */
    public function historyForReport(int $reportId): array;
}
