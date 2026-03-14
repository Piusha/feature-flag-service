<?php

namespace App\Modules\EventLogs\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    protected $table = 'event_logs';

    protected $fillable = [
        'event_name',
        'aggregate_type',
        'aggregate_id',
        'actor_id',
        'actor_type',
        'context',
        'payload',
        'occurred_at',
    ];

    protected $casts = [
        'context' => 'array',
        'payload' => 'array',
        'occurred_at' => 'immutable_datetime',
    ];
}
