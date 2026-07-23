<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professional_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Índice para consultas rápidas
            $table->index(['user_id', 'day_of_week', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professional_availabilities');
    }
};