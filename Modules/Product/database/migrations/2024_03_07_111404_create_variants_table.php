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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('offer_price', 10, 2)->nullable();
            $table->enum('offer_price_type', ['fixed', 'percentage'])->nullable()->default('fixed');
            $table->date('offer_price_start')->nullable();
            $table->date('offer_price_end')->nullable();
            $table->boolean('is_flash_deal')->default(0);
            $table->date('flash_deal_start')->nullable();
            $table->date('flash_deal_end')->nullable();
            $table->decimal('flash_deal_price', 10, 2)->default(0.00);
            $table->integer('flash_deal_qty')->default(0);
            $table->boolean('is_default')->default(0);
            $table->boolean('status')->default(1);
            $table->string('image')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
