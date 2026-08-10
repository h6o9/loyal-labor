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
        Schema::create('admin_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('action'); // created, updated, deleted, login, logout, etc.
            $table->string('subject_type')->nullable(); // Model class name
            $table->unsignedBigInteger('subject_id')->nullable(); // Model ID
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('properties')->nullable(); // Additional data
            $table->timestamps();

            $table->index(['admin_id', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activities');
    }
};
