<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('district_cities')) {
            return;
        }

        Schema::create('district_cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('district_id');
            $table->string('name', 120);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('district_id');
            $table->unique(['district_id', 'name'], 'district_city_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('district_cities');
    }
};
