<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adicionar coluna photo se não existir
            if (!Schema::hasColumn('users', 'photo')) {
                $table->string('photo')->nullable()->after('email');
            }
            
            // Adicionar coluna must_change_password se não existir
            if (!Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false)->after('password');
            }
            
            // Adicionar coluna is_active se não existir
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['photo', 'must_change_password', 'is_active']);
        });
    }
};