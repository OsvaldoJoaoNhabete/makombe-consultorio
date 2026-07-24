<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tornar email opcional (nullable)
            $table->string('email')->nullable()->change();
            
            // Adicionar telefone único para login
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->unique()->nullable()->after('email');
            }
            
            // Adicionar tipo de usuário (staff ou patient)
            if (!Schema::hasColumn('users', 'type')) {
                $table->enum('type', ['staff', 'patient'])->default('staff')->after('password');
            }
            
            // Adicionar estado da conta
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropUnique(['phone']);
            $table->dropColumn(['phone', 'type', 'is_active']);
        });
    }
};