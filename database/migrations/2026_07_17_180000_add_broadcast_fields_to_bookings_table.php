<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('bookings', 'booking_type')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('booking_type', 20)->default('direct')->after('technician_id');
            });
        }

        if (!Schema::hasColumn('bookings', 'service_category_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('service_category_id')->nullable()->after('booking_type');
            });
        }

        if (!Schema::hasColumn('bookings', 'district_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('district_id')->nullable()->after('service_category_id');
            });
        }

        try {
            DB::statement('ALTER TABLE `bookings` MODIFY `technician_id` BIGINT UNSIGNED NULL');
        } catch (\Throwable $e) {
            // Ignore if already nullable or driver differs
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'district_id')) {
                $table->dropColumn('district_id');
            }
            if (Schema::hasColumn('bookings', 'service_category_id')) {
                $table->dropColumn('service_category_id');
            }
            if (Schema::hasColumn('bookings', 'booking_type')) {
                $table->dropColumn('booking_type');
            }
        });
    }
};
