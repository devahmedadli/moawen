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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignId('specialization_id')->nullable()->constrained('specializations')->nullOnDelete();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->text('bio')->default('');
            $table->json('skills')->nullable();
            $table->string('country')->nullable();
            $table->string('image')->default('storage/users-imgs/moawen.svg');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('role', ['freelancer', 'client', 'admin'])->default('client');
            $table->boolean('is_2fa_enabled')->default(false);
            $table->string('kyc_status')->nullable();
            $table->enum('account_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->float('average_rating')->default(0.0);
            $table->string('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
