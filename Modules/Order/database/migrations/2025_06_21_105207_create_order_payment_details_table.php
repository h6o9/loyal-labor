<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\app\Http\Enums\PaymentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_payment_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('coupon_code')->nullable();
            $table->decimal('total_discount', 12, 2)->nullable();
            $table->decimal('payable_amount_without_rate', 12, 2);
            $table->decimal('payable_amount', 12, 2);
            $table->string('payable_currency')->nullable();
            $table->string('paid_amount')->default(0);
            $table->string('transaction_id')->nullable();
            $table->text('payment_details')->nullable();
            $table->string('payment_method');
            $table->enum('payment_status', PaymentStatus::getAll())->default(PaymentStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payment_details');
    }
};
