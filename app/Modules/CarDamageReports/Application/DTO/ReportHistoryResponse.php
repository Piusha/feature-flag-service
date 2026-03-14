<?php

namespace App\Modules\CarDamageReports\Application\DTO;

final class ReportHistoryResponse
{
    public function __construct(
        public readonly int $id,
        public readonly int $reportId,
        public readonly string $eventType,
        public readonly string $description,
        public readonly ?string $createdAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'report_id' => $this->reportId,
            'event_type' => $this->eventType,
            'description' => $this->description,
            'created_at' => $this->createdAt,
        ];
    }
}
