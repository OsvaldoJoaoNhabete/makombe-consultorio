<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Adicionar Tipo Sanguíneo
            if (!Schema::hasColumn('patients', 'blood_type')) {
                $table->string('blood_type')->nullable()->after('gender');
            }
            
            // Adicionar Contactos de Emergência
            if (!Schema::hasColumn('patients', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('address');
            }
            if (!Schema::hasColumn('patients', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('patients', 'emergency_contact_relation')) {
                $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_phone');
            }
            
            // Adicionar Dados do Seguro e Médico
            if (!Schema::hasColumn('patients', 'insurance_id')) {
                $table->foreignId('insurance_id')->nullable()->after('emergency_contact_relation')->constrained('insurances')->nullOnDelete();
            }
            if (!Schema::hasColumn('patients', 'policy_number')) {
                $table->string('policy_number')->nullable()->after('insurance_id');
            }
            if (!Schema::hasColumn('patients', 'assigned_doctor_id')) {
                $table->foreignId('assigned_doctor_id')->nullable()->after('policy_number')->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['insurance_id']);
            $table->dropForeign(['assigned_doctor_id']);
            
            $table->dropColumn([
                'blood_type',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relation',
                'insurance_id',
                'policy_number',
                'assigned_doctor_id',
            ]);
        });
    }
};