<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            if (!Schema::hasColumn('quotes', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('quotes', 'discount_type')) {
                $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed')->after('discount');
            }
            if (!Schema::hasColumn('quotes', 'final_amount')) {
                $table->decimal('final_amount', 10, 2)->default(0)->after('discount_type');
            }
            if (!Schema::hasColumn('quotes', 'valid_until')) {
                $table->date('valid_until')->nullable()->after('final_amount');
            }
            if (!Schema::hasColumn('quotes', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('valid_until');
            }
            if (!Schema::hasColumn('quotes', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('sent_at');
            }
            if (!Schema::hasColumn('quotes', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn([
                'discount', 'discount_type', 'final_amount',
                'valid_until', 'sent_at', 'approved_at', 'rejected_at'
            ]);
        });
    }
};