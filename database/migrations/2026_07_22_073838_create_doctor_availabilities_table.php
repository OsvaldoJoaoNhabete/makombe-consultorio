<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Médico/Enfermeiro
            $table->string('day_of_week'); // 'monday', 'tuesday', etc.
            $table->time('start_time'); // Ex: 08:00:00
            $table->time('end_time');   // Ex: 12:00:00
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_availabilities');
    }
};