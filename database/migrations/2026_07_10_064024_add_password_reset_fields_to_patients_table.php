<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'password_reset_token')) {
                $table->string('password_reset_token')->nullable()->after('password');
            }
            if (!Schema::hasColumn('patients', 'password_reset_expires')) {
                $table->timestamp('password_reset_expires')->nullable()->after('password_reset_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['password_reset_token', 'password_reset_expires']);
        });
    }
};