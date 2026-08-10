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
        Schema::create('order_status_change_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['payment_status', 'order_status'])->default('order_status');
            $table->string('from_status')->default('pending');
            $table->string('to_status');
            $table->text('message')->nullable();
            $table->enum('change_by', ['admin', 'user', 'system'])->default('system');
            $table->unsignedBigInteger('changed_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_change_histories');
    }
};
