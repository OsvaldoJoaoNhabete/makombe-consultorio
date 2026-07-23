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
        Schema::table('users', function (Blueprint $table) {
            // Adiciona a coluna 'role' após o campo 'email'
            // Valores padrão: null (vazio até ser definido)
            $table->string('role')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove a coluna 'role' se precisarmos reverter
            $table->dropColumn('role');
        });
    }
};