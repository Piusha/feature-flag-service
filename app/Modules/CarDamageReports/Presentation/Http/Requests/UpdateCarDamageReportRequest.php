<?php

namespace App\Modules\CarDamageReports\Presentation\Http\Requests;

use App\Http\Requests\ApiFormRequest;
use App\Modules\CarDamageReports\Application\DTO\UpdateCarDamageReportCommand;
use App\Modules\CarDamageReports\Domain\Enums\ReportSeverity;
use App\Modules\CarDamageReports\Domain\Enums\ReportStatus;
use Illuminate\Validation\Rule;

class UpdateCarDamageReportRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['sometimes', 'required', 'string', 'max:255'],
            'vehicle_registration' => ['sometimes', 'required', 'string', 'max:100'],
            'vehicle_model' => ['sometimes', 'required', 'string', 'max:255'],
            'damage_description' => ['sometimes', 'required', 'string'],
            'severity' => ['sometimes', 'required', Rule::in(['low', 'medium', 'high'])],
            'repair_estimate_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['sometimes', 'required', Rule::in(['draft', 'submitted', 'reviewed'])],
            'incident_date' => ['sometimes', 'required', 'date'],
            'incident_location' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function toCommand(): UpdateCarDamageReportCommand
    {
        $data = $this->validated();

        return new UpdateCarDamageReportCommand(
            reportId: (int) $this->route('id'),
            customerName: $data['customer_name'] ?? null,
            hasCustomerName: array_key_exists('customer_name', $data),
            vehicleRegistration: $data['vehicle_registration'] ?? null,
            hasVehicleRegistration: array_key_exists('vehicle_registration', $data),
            vehicleModel: $data['vehicle_model'] ?? null,
            hasVehicleModel: array_key_exists('vehicle_model', $data),
            damageDescription: $data['damage_description'] ?? null,
            hasDamageDescription: array_key_exists('damage_description', $data),
            severity: array_key_exists('severity', $data) ? ReportSeverity::from($data['severity']) : null,
            hasSeverity: array_key_exists('severity', $data),
            repairEstimateAmount: array_key_exists('repair_estimate_amount', $data)
                ? ($data['repair_estimate_amount'] !== null ? (float) $data['repair_estimate_amount'] : null)
                : null,
            hasRepairEstimateAmount: array_key_exists('repair_estimate_amount', $data),
            status: array_key_exists('status', $data) ? ReportStatus::from($data['status']) : null,
            hasStatus: array_key_exists('status', $data),
            incidentDate: array_key_exists('incident_date', $data) ? new \DateTimeImmutable($data['incident_date']) : null,
            hasIncidentDate: array_key_exists('incident_date', $data),
            incidentLocation: $data['incident_location'] ?? null,
            hasIncidentLocation: array_key_exists('incident_location', $data),
        );
    }
}
