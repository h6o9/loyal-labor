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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->default(0);
            $table->string('coupon_code');
            $table->decimal('discount', 8, 2);
            $table->enum('apply_for', ['product', 'category', 'all'])->default('all');
            $table->unsignedDecimal('minimum_spend', 18, 4)->nullable();
            $table->unsignedBigInteger('usage_limit_per_coupon')->nullable();
            $table->unsignedInteger('usage_limit_per_customer')->nullable();
            $table->boolean('can_use_with_campaign')->default(false);
            $table->boolean('free_shipping')->default(false);
            $table->boolean('is_percent')->default(1);
            $table->timestamp('start_date');
            $table->timestamp('expired_date')->nullable();
            $table->boolean('is_never_expired')->default(0);
            $table->unsignedInteger('used')->default(0);
            $table->boolean('show_homepage')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
