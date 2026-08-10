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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('status')->default(1);
            $table->string('image')->nullable();
            $table->enum('type', ['physical', 'digital'])->default('physical');
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('position')->nullable();
            $table->boolean('is_searchable');
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_top')->default(0);
            $table->boolean('is_popular')->default(0);
            $table->boolean('is_trending')->default(0);
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->tinyInteger('theme')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
