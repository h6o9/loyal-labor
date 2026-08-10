<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\app\Http\Enums\OrderStatus;
use Modules\Order\app\Models\OrderPaymentDetails;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('order_id')->unique()->nullable();
            $table->boolean('is_guest_order')->default(0);
            $table->integer('user_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->text('note')->nullable();
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('shipping', 12, 2)->default(0);
            $table->decimal('gateway_fee', 12, 2)->default(0);
            $table->decimal('sub_total', 12, 2);
            $table->string('coupon_code')->nullable();
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->foreignIdFor(OrderPaymentDetails::class);
            $table->enum('order_status', OrderStatus::getAll())->default(OrderStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
