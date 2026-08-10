<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            
            // Basic Information
            $table->string('shop_name');
            $table->string('category');
            $table->string('owner_name');
            $table->string('phone_number');
            $table->string('whatsapp_number')->nullable();
            $table->text('address');
            $table->text('about_shop')->nullable();
            
            // Location
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Business Hours
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            
            // Status & Verification
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            // Visit Statistics
            $table->integer('total_visits')->default(0);
            $table->timestamp('last_visited_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('staff_id');
            $table->index('category');
            $table->index('status');
            $table->index('is_verified');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('shops');
    }
}