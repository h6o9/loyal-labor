<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('bookings', 'completion_otp')) {
                    $table->string('completion_otp', 6)->nullable()->after('completed_at');
                }
                if (!Schema::hasColumn('bookings', 'completion_otp_expires_at')) {
                    $table->timestamp('completion_otp_expires_at')->nullable()->after('completion_otp');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (Schema::hasColumn('bookings', 'completion_otp_expires_at')) {
                    $table->dropColumn('completion_otp_expires_at');
                }
                if (Schema::hasColumn('bookings', 'completion_otp')) {
                    $table->dropColumn('completion_otp');
                }
            });
        }
    }
};
