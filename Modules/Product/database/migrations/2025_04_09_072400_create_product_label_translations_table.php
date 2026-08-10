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
        Schema::create('product_label_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_label_id')->constrained('product_labels')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('lang_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_label_translations');
    }
};
