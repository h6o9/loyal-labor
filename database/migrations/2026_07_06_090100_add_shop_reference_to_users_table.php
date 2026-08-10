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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'shop_reference_code')) {
                $table->string('shop_reference_code')->nullable()->after('subscription_id');
            }

            if (!Schema::hasColumn('users', 'referral_shop_id')) {
                $table->unsignedBigInteger('referral_shop_id')->nullable()->after('shop_reference_code');
                $table->foreign('referral_shop_id')->references('id')->on('shops')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'referral_shop_id')) {
                $table->dropForeign(['referral_shop_id']);
                $table->dropColumn('referral_shop_id');
            }

            if (Schema::hasColumn('users', 'shop_reference_code')) {
                $table->dropColumn('shop_reference_code');
            }
        });
    }
};
