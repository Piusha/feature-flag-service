<?php

namespace App\Modules\CarDamageReports\Presentation\Http\Resources;

use App\Modules\CarDamageReports\Application\DTO\CarDamageReportResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CarDamageReportResponse */
class CarDamageReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->resource->toArray();
    }
}
