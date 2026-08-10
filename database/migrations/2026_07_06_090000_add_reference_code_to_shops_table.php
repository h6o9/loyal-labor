<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('shops', 'reference_code')) {
            Schema::table('shops', function (Blueprint $table) {
                $table->string('reference_code')->nullable()->unique()->after('id');
            });
        }

        // Backfill existing shops with a unique reference code
        $shops = DB::table('shops')->whereNull('reference_code')->orWhere('reference_code', '')->get();

        foreach ($shops as $shop) {
            do {
                $code = '#shop-' . random_int(1000, 9999);
            } while (DB::table('shops')->where('reference_code', $code)->exists());

            DB::table('shops')->where('id', $shop->id)->update(['reference_code' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('shops', 'reference_code')) {
            Schema::table('shops', function (Blueprint $table) {
                $table->dropColumn('reference_code');
            });
        }
    }
};
