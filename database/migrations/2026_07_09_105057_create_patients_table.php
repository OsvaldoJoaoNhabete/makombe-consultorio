<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('nid')->unique();
            $table->string('bi_number')->nullable()->unique();
            $table->date('birth_date');
            $table->enum('gender', ['masculino', 'feminino', 'outro']);
            $table->string('phone', 9)->unique();
            $table->string('email')->unique();
            $table->text('address')->nullable();
            $table->text('medical_history')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('photo_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};