<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            // Especialidade solicitada pelo paciente
            if (!Schema::hasColumn('consultations', 'specialty_id')) {
                $table->foreignId('specialty_id')->nullable()->after('doctor_id')->constrained('specialties')->nullOnDelete();
            }
            
            // Indica se é consulta urgente
            if (!Schema::hasColumn('consultations', 'is_urgent')) {
                $table->boolean('is_urgent')->default(false)->after('specialty_id');
            }
            
            // Avaliação do paciente (1-5 estrelas)
            if (!Schema::hasColumn('consultations', 'rating')) {
                $table->tinyInteger('rating')->nullable()->after('status')->comment('1 a 5 estrelas');
            }
            
            // Comentário da avaliação
            if (!Schema::hasColumn('consultations', 'review_comment')) {
                $table->text('review_comment')->nullable()->after('rating');
            }
            
            // Data em que foi avaliado
            if (!Schema::hasColumn('consultations', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('review_comment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['specialty_id']);
            $table->dropColumn(['specialty_id', 'is_urgent', 'rating', 'review_comment', 'reviewed_at']);
        });
    }
};