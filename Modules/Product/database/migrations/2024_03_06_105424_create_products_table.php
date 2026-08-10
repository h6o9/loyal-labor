<?php

use App\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\app\Models\Brand;
use Modules\Product\app\Models\UnitType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->foreignIdFor(Brand::class)->nullable();
            $table->foreignIdFor(Vendor::class)->nullable();
            $table->foreignIdFor(UnitType::class)->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('video_link')->nullable();
            $table->tinyInteger('is_cash_delivery')->default(0);
            $table->tinyInteger('is_return')->default(0);
            $table->integer('return_policy_id')->nullable();
            $table->tinyInteger('is_featured')->unsigned()->default(0);
            $table->tinyInteger('is_popular')->unsigned()->default(0);
            $table->tinyInteger('is_best_selling')->unsigned()->default(0);
            $table->tinyInteger('allow_checkout_when_out_of_stock')->unsigned()->default(0);
            $table->decimal('price', 10, 2)->unsigned()->nullable();
            $table->decimal('offer_price', 10, 2)->unsigned()->nullable();
            $table->enum('offer_price_type', ['fixed', 'percentage'])->nullable()->default('fixed');
            $table->date('offer_price_start')->nullable();
            $table->date('offer_price_end')->nullable();
            $table->boolean('is_flash_deal')->default(0);
            $table->string('flash_deal_image')->nullable();
            $table->date('flash_deal_start')->nullable();
            $table->date('flash_deal_end')->nullable();
            $table->decimal('flash_deal_price', 10, 2)->nullable()->default(0.00);
            $table->integer('flash_deal_qty')->nullable()->default(0);
            $table->integer('manage_stock')->default(0);
            $table->enum('stock_status', ['in_stock', 'out_of_stock'])->default('in_stock');
            $table->boolean('status')->default(1);
            $table->boolean('is_approved')->default(0);
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->float('length')->nullable();
            $table->float('wide')->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->unsignedBigInteger('viewed')->unsigned()->default(0);
            $table->unsignedSmallInteger('theme')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
