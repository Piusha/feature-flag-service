<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_history', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('report_id')->constrained('car_damage_reports')->cascadeOnDelete();
            $table->string('event_type');
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_history');
    }
};
