<?php

namespace App\Modules\CarDamageReports\Domain\Enums;

enum ReportStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case REVIEWED = 'reviewed';
}
