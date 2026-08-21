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
                if (!Schema::hasColumn('bookings', 'latitude')) {
                    $table->decimal('latitude', 10, 8)->nullable()->after('city');
                }
                if (!Schema::hasColumn('bookings', 'longitude')) {
                    $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
                }
                if (!Schema::hasColumn('bookings', 'current_radius_km')) {
                    $table->unsignedInteger('current_radius_km')->nullable()->after('expires_at');
                }
                if (!Schema::hasColumn('bookings', 'last_expand_prompt_at')) {
                    $table->timestamp('last_expand_prompt_at')->nullable()->after('current_radius_km');
                }
            });
        }

        if (!Schema::hasTable('booking_broadcast_notified')) {
            Schema::create('booking_broadcast_notified', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('booking_id');
                $table->unsignedBigInteger('technician_id');
                $table->unsignedInteger('radius_km')->nullable();
                $table->decimal('distance_km', 8, 2)->nullable();
                $table->timestamps();
                $table->unique(['booking_id', 'technician_id']);
            });
        }

        if (!Schema::hasTable('booking_individual_offers')) {
            Schema::create('booking_individual_offers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('booking_id');
                $table->unsignedBigInteger('technician_id');
                $table->string('status', 40)->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_individual_offers');
        Schema::dropIfExists('booking_broadcast_notified');
    }
};
