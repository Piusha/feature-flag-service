<?php

namespace App\Modules\CarDamageReports\Application\DTO;

final class ReportPhotoResponse
{
    public function __construct(
        public readonly int $id,
        public readonly int $reportId,
        public readonly string $fileName,
        public readonly string $filePath,
        public readonly string $mimeType,
        public readonly int $size,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'report_id' => $this->reportId,
            'file_name' => $this->fileName,
            'file_path' => $this->filePath,
            'mime_type' => $this->mimeType,
            'size' => $this->size,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
