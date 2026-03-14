<?php

namespace App\Modules\CarDamageReports\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarDamageReport extends Model
{
    protected $table = 'car_damage_reports';

    protected $fillable = [
        'reference_number',
        'customer_name',
        'vehicle_registration',
        'vehicle_model',
        'damage_description',
        'severity',
        'repair_estimate_amount',
        'status',
        'incident_date',
        'incident_location',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'repair_estimate_amount' => 'decimal:2',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(ReportPhoto::class, 'report_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(ReportHistory::class, 'report_id')->latest('created_at');
    }
}
