<?php

use App\Models\Admin;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\KnowYourClient\app\Enums\KYCStatusEnum;
use Modules\KnowYourClient\app\Models\KycType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kyc_information', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(KycType::class);
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Vendor::class)->nullable();
            $table->foreignIdFor(Admin::class)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('file')->nullable();
            $table->text('message')->nullable();
            $table->tinyInteger('status')->default(KYCStatusEnum::PENDING->value)
                ->comment('0: Pending, 1: Approved, 2: Rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_information');
    }
};
