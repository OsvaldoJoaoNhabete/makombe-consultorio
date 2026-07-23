<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Especialidade principal do médico
            if (!Schema::hasColumn('users', 'specialty_id')) {
                $table->foreignId('specialty_id')->nullable()->after('email')->constrained('specialties')->nullOnDelete();
            }
            
            // Número de telemóvel (para login)
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['specialty_id']);
            $table->dropColumn(['specialty_id', 'phone']);
        });
    }
};