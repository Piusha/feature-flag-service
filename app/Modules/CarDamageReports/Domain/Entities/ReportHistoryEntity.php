<?php

namespace App\Modules\CarDamageReports\Domain\Entities;

final class ReportHistoryEntity
{
    public function __construct(
        private readonly ?int $id,
        private readonly int $reportId,
        private readonly string $eventType,
        private readonly string $description,
        private readonly ?\DateTimeImmutable $createdAt = null,
    ) {
    }

    public function id(): ?int { return $this->id; }
    public function reportId(): int { return $this->reportId; }
    public function eventType(): string { return $this->eventType; }
    public function description(): string { return $this->description; }
    public function createdAt(): ?\DateTimeImmutable { return $this->createdAt; }
}
