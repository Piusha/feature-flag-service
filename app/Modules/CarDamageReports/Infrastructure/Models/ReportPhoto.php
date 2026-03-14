<?php

namespace App\Modules\CarDamageReports\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportPhoto extends Model
{
    protected $table = 'report_photos';

    protected $fillable = [
        'report_id',
        'file_name',
        'file_path',
        'mime_type',
        'size',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(CarDamageReport::class, 'report_id');
    }
}
