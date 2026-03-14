<?php

namespace App\Modules\CarDamageReports\Application\DTO;

use Illuminate\Http\UploadedFile;

final class UploadReportPhotoCommand
{
    public function __construct(
        public readonly int $reportId,
        public readonly UploadedFile $photo,
    ) {
    }
}
