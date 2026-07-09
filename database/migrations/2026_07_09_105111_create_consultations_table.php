<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('scheduled_at');
            $table->enum('type', ['presencial', 'teleconsulta', 'domicilio'])->default('presencial');
            $table->enum('status', ['agendada', 'confirmada', 'em_andamento', 'concluida', 'cancelada', 'faltou'])->default('agendada');
            $table->foreignId('insurance_id')->nullable()->constrained('insurances')->nullOnDelete();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('insurance_coverage', 10, 2)->default(0);
            $table->decimal('patient_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['pendente', 'pago', 'parcial', 'isento'])->default('pendente');
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->text('clinical_notes')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('prescription')->nullable();
            $table->text('observations')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('video_call_started_at')->nullable();
            $table->timestamp('video_call_ended_at')->nullable();
            $table->timestamp('patient_notified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};