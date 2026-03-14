<?php

namespace Database\Seeders;

use App\Modules\CarDamageReports\Infrastructure\Models\CarDamageReport;
use Illuminate\Database\Seeder;

class CarDamageReportSeeder extends Seeder
{
    public function run(): void
    {
        $reportA = CarDamageReport::query()->updateOrCreate(
            ['reference_number' => 'CDR-20260313-AAA001'],
            [
                'customer_name' => 'Alex Johnson',
                'vehicle_registration' => 'AB12CDE',
                'vehicle_model' => 'Toyota Corolla',
                'damage_description' => 'Front bumper scratched after low speed collision.',
                'severity' => 'low',
                'repair_estimate_amount' => 320.00,
                'status' => 'submitted',
                'incident_date' => now()->subDays(4)->toDateString(),
                'incident_location' => 'Main Street Parking Lot',
            ]
        );

        $reportA->history()->updateOrCreate(
            ['event_type' => 'report_created'],
            ['description' => 'Initial report created.']
        );

        $reportA->history()->updateOrCreate(
            ['event_type' => 'report_reviewed'],
            ['description' => 'Report was reviewed by an assessor.']
        );

        CarDamageReport::query()->updateOrCreate(
            ['reference_number' => 'CDR-20260313-BBB002'],
            [
                'customer_name' => 'Maria Chen',
                'vehicle_registration' => 'XY34ZRT',
                'vehicle_model' => 'Honda Civic',
                'damage_description' => 'Rear door dent and paint transfer.',
                'severity' => 'medium',
                'repair_estimate_amount' => 980.00,
                'status' => 'draft',
                'incident_date' => now()->subDays(2)->toDateString(),
                'incident_location' => 'North Avenue',
            ]
        );
    }
}
