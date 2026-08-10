<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('app_notifications') && !Schema::hasColumn('app_notifications', 'target_user_ids')) {
            Schema::table('app_notifications', function (Blueprint $table) {
                $table->json('target_user_ids')->nullable()->after('target_audience');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('app_notifications', 'target_user_ids')) {
            Schema::table('app_notifications', function (Blueprint $table) {
                $table->dropColumn('target_user_ids');
            });
        }
    }
};
