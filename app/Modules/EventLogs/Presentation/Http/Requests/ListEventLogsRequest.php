<?php

namespace App\Modules\EventLogs\Presentation\Http\Requests;

use App\Http\Requests\ApiFormRequest;
use App\Modules\EventLogs\Domain\ValueObjects\EventLogQueryFilters;

class ListEventLogsRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_name' => ['nullable', 'string', 'max:255'],
            'aggregate_type' => ['nullable', 'string', 'max:255'],
            'aggregate_id' => ['nullable', 'string', 'max:255'],
            'actor_id' => ['nullable', 'string', 'max:255'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toFilters(): EventLogQueryFilters
    {
        $data = $this->validated();
        $occurredFrom = isset($data['date_from'])
            ? (new \DateTimeImmutable($data['date_from']))->setTime(0, 0, 0)
            : null;
        $occurredTo = isset($data['date_to'])
            ? (new \DateTimeImmutable($data['date_to']))->setTime(23, 59, 59)
            : null;

        return new EventLogQueryFilters(
            eventName: $data['event_name'] ?? null,
            aggregateType: $data['aggregate_type'] ?? null,
            aggregateId: $data['aggregate_id'] ?? null,
            actorId: $data['actor_id'] ?? null,
            occurredFrom: $occurredFrom,
            occurredTo: $occurredTo,
        );
    }
}
