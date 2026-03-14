<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_damage_reports', function (Blueprint $table): void {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('customer_name');
            $table->string('vehicle_registration');
            $table->string('vehicle_model');
            $table->text('damage_description');
            $table->enum('severity', ['low', 'medium', 'high']);
            $table->decimal('repair_estimate_amount', 12, 2)->nullable();
            $table->enum('status', ['draft', 'submitted', 'reviewed']);
            $table->date('incident_date');
            $table->string('incident_location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_damage_reports');
    }
};
