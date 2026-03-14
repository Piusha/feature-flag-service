<?php

namespace App\Modules\CarDamageReports\Presentation\Http\Requests;

use App\Modules\CarDamageReports\Application\DTO\CreateCarDamageReportCommand;
use App\Modules\CarDamageReports\Domain\Enums\ReportSeverity;
use App\Modules\CarDamageReports\Domain\Enums\ReportStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarDamageReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'vehicle_registration' => ['required', 'string', 'max:100'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'damage_description' => ['required', 'string'],
            'severity' => ['required', Rule::in(['low', 'medium', 'high'])],
            'repair_estimate_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'submitted', 'reviewed'])],
            'incident_date' => ['required', 'date'],
            'incident_location' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function toCommand(): CreateCarDamageReportCommand
    {
        $data = $this->validated();

        return new CreateCarDamageReportCommand(
            customerName: $data['customer_name'],
            vehicleRegistration: $data['vehicle_registration'],
            vehicleModel: $data['vehicle_model'],
            damageDescription: $data['damage_description'],
            severity: ReportSeverity::from($data['severity']),
            repairEstimateAmount: isset($data['repair_estimate_amount']) ? (float) $data['repair_estimate_amount'] : null,
            status: ReportStatus::from($data['status']),
            incidentDate: new \DateTimeImmutable($data['incident_date']),
            incidentLocation: $data['incident_location'] ?? null,
        );
    }
}
