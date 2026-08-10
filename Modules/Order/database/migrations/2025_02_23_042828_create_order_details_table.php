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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('product_id');
            $table->foreignId('vendor_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->integer('qty');
            $table->decimal('price', 15);
            $table->decimal('tax_amount', 15);
            $table->decimal('total_price', 15);
            $table->text('options')->nullable();
            $table->string('product_name');
            $table->string('product_thumbnail')->nullable();
            $table->string('product_sku');
            $table->integer('commission_rate')->nullable();
            $table->decimal('commission', 15)->default(0);
            $table->boolean('is_variant')->default(0);
            $table->boolean('is_flash_deal')->default(0);
            $table->string('measurement')->nullable();
            $table->float('weight')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
