<?php

namespace App\Modules\CarDamageReports\Presentation\Http\Resources;

use App\Modules\CarDamageReports\Application\DTO\ReportPhotoResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ReportPhotoResponse */
class ReportPhotoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->resource->toArray();
    }
}
