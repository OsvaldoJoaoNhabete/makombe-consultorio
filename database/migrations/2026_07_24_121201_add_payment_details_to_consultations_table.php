<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            // Detalhes específicos do pagamento (ilustrativo por agora)
            $table->string('payment_provider')->nullable()->after('payment_method'); // Ex: M-Pesa, BCI, AMS
            $table->string('payment_reference')->nullable()->after('payment_provider'); // Ex: Nº de Transação, Nº de Apólice, IBAN
            $table->text('home_visit_address')->nullable()->after('notes'); // Endereço completo se for domicílio
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn(['payment_provider', 'payment_reference', 'home_visit_address']);
        });
    }
};