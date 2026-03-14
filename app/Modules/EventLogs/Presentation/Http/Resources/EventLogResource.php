<?php

namespace App\Modules\EventLogs\Presentation\Http\Resources;

use App\Modules\EventLogs\Domain\Entities\EventLogEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin EventLogEntry */
class EventLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'event_name' => $this->resource->eventName,
            'aggregate_type' => $this->resource->aggregateType,
            'aggregate_id' => $this->resource->aggregateId,
            'actor_id' => $this->resource->actorId,
            'actor_type' => $this->resource->actorType,
            'context' => $this->resource->context,
            'payload' => $this->resource->payload,
            'occurred_at' => $this->resource->occurredAt->format(DATE_ATOM),
            'created_at' => $this->resource->createdAt?->format(DATE_ATOM),
            'updated_at' => $this->resource->updatedAt?->format(DATE_ATOM),
        ];
    }
}
