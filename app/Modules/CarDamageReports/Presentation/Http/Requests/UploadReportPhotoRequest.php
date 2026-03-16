<?php

namespace App\Modules\CarDamageReports\Presentation\Http\Requests;

use App\Http\Requests\ApiFormRequest;
use App\Modules\CarDamageReports\Application\DTO\UploadReportPhotoCommand;

class UploadReportPhotoRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => ['required', 'file', 'image', 'max:5120'],
            'user_id' => ['required', 'string', 'max:255'],
        ];
    }

    public function toCommand(int $reportId): UploadReportPhotoCommand
    {
        return new UploadReportPhotoCommand(
            reportId: $reportId,
            photo: $this->file('photo'),
        );
    }
}
