<?php

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\app\Http\Enums\PaymentStatus;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderDetails;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;
use Modules\Product\app\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallet_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Vendor::class)->nullable();
            $table->foreignIdFor(Product::class)->nullable();
            $table->foreignIdFor(Order::class)->nullable();
            $table->foreignIdFor(OrderDetails::class)->nullable();
            $table->foreignIdFor(WithdrawRequest::class)->nullable();
            $table->enum('transaction_type', ['credit', 'debit'])->default('credit');
            $table->decimal('amount', 8, 2);
            $table->string('transaction_id');
            $table->string('payment_gateway');
            $table->enum('payment_status', PaymentStatus::getAll())->default(PaymentStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_histories');
    }
};
