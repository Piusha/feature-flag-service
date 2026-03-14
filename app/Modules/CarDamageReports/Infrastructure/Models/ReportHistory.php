<?php

namespace App\Modules\CarDamageReports\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportHistory extends Model
{
    protected $table = 'report_history';

    public const UPDATED_AT = null;

    protected $fillable = [
        'report_id',
        'event_type',
        'description',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(CarDamageReport::class, 'report_id');
    }
}
