<?php

namespace App\Modules\CarDamageReports\Domain\Enums;

enum ReportSeverity: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
}
