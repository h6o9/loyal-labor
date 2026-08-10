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
        Schema::table('staff', function (Blueprint $table) {
            if (!Schema::hasColumn('staff', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('is_active');
                $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            }
        });

        Schema::table('shops', function (Blueprint $table) {
            if (!Schema::hasColumn('shops', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('staff_id');
                $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropColumn('district_id');
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropColumn('district_id');
        });
    }
};
