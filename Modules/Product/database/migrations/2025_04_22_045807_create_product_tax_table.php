<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\app\Models\Product;
use Modules\Tax\app\Models\Tax;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_tax', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained('products')->cascadeOnDelete();
            $table->foreignIdFor(Tax::class)->constrained('taxes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tax');
    }
};
