<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subscriptions') && !Schema::hasColumn('subscriptions', 'plan_type')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->string('plan_type', 50)->default('basic_plan')->after('name');
            });
        }

        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'payment_method')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('payment_method')->nullable()->after('payment_status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('subscriptions', 'plan_type')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('plan_type');
            });
        }

        if (Schema::hasColumn('users', 'payment_method')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('payment_method');
            });
        }
    }
};
