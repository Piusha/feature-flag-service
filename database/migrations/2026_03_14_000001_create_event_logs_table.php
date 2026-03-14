<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('event_name');
            $table->string('aggregate_type');
            $table->string('aggregate_id');
            $table->string('actor_id')->nullable();
            $table->string('actor_type')->nullable();
            $table->json('context')->nullable();
            $table->json('payload');
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index('event_name');
            $table->index(['aggregate_type', 'aggregate_id']);
            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_logs');
    }
};
