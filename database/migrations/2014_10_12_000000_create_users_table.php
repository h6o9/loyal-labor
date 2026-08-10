<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('country_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('city_id')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('address')->nullable();
            $table->string('forget_password_token')->nullable();
            $table->string('verification_token')->nullable();
            $table->string('is_banned')->default('no');
            $table->string('status')->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1001');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
