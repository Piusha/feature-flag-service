<?php

namespace App\Modules\CarDamageReports\Application\Contracts;

use App\Modules\CarDamageReports\Application\DTO\CarDamageReportListResponse;
use App\Modules\CarDamageReports\Application\DTO\CarDamageReportResponse;
use App\Modules\CarDamageReports\Application\DTO\CreateCarDamageReportCommand;
use App\Modules\CarDamageReports\Application\DTO\ReportHistoryResponse;
use App\Modules\CarDamageReports\Application\DTO\ReportPhotoResponse;
use App\Modules\CarDamageReports\Application\DTO\UpdateCarDamageReportCommand;
use App\Modules\CarDamageReports\Application\DTO\UploadReportPhotoCommand;

interface ManageCarDamageReportsUseCaseInterface
{
    public function listPaginated(int $perPage = 15, int $page = 1): CarDamageReportListResponse;

    public function find(int $id): ?CarDamageReportResponse;

    public function create(CreateCarDamageReportCommand $command): CarDamageReportResponse;

    public function update(UpdateCarDamageReportCommand $command): ?CarDamageReportResponse;

    public function addPhoto(UploadReportPhotoCommand $command): ?ReportPhotoResponse;

    /**
     * @return ReportHistoryResponse[]|null
     */
    public function history(int $reportId): ?array;
}
