<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->comment('1 a 5 estrelas');
            $table->text('comment')->nullable();
            $table->timestamps();
            
            // Garantir que um paciente só avalia uma consulta uma vez
            $table->unique(['consultation_id', 'patient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_ratings');
    }
};