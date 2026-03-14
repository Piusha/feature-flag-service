<?php

namespace App\Modules\CarDamageReports\Domain\Entities;

final class ReportPhotoEntity
{
    public function __construct(
        private readonly ?int $id,
        private readonly int $reportId,
        private readonly string $fileName,
        private readonly string $filePath,
        private readonly string $mimeType,
        private readonly int $size,
        private readonly ?\DateTimeImmutable $createdAt = null,
        private readonly ?\DateTimeImmutable $updatedAt = null,
    ) {
    }

    public function id(): ?int { return $this->id; }
    public function reportId(): int { return $this->reportId; }
    public function fileName(): string { return $this->fileName; }
    public function filePath(): string { return $this->filePath; }
    public function mimeType(): string { return $this->mimeType; }
    public function size(): int { return $this->size; }
    public function createdAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function updatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
}
