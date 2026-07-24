<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            // Sugestão 5: Consulta Urgente
            if (!Schema::hasColumn('consultations', 'is_urgent')) {
                $table->boolean('is_urgent')->default(false)->after('type');
            }
            
            // Sugestão 6: Modalidade de Pagamento (Ilustrativa no agendamento)
            if (!Schema::hasColumn('consultations', 'payment_method')) {
                $table->enum('payment_method', ['dinheiro', 'tpv_multicaixa', 'transferencia', 'seguro'])->default('dinheiro')->after('is_urgent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn(['is_urgent', 'payment_method']);
        });
    }
};