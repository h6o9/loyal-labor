<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'work_in_progress_at')) {
                $table->timestamp('work_in_progress_at')->nullable()->after('work_started_at');
            }
        });

        try {
            DB::statement("ALTER TABLE `bookings` MODIFY `status` ENUM('pending','accepted','on_the_way','work_started','work_in_progress','rejected','completed','cancelled','expired') NOT NULL DEFAULT 'pending'");
        } catch (\Throwable $e) {
            // Ignore if driver does not support ENUM modify
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `bookings` MODIFY `status` ENUM('pending','accepted','on_the_way','work_started','rejected','completed','cancelled','expired') NOT NULL DEFAULT 'pending'");
        } catch (\Throwable $e) {
            // ignore
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'work_in_progress_at')) {
                $table->dropColumn('work_in_progress_at');
            }
        });
    }
};
