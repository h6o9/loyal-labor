<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('technician_work_galleries')) {
            Schema::create('technician_work_galleries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
                $table->string('image');
                $table->string('caption')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_work_galleries');
    }
};
