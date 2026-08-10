<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_category_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_category_id')->constrained('service_categories')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'service_category_id'], 'uq_user_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_category_user');
    }
};
