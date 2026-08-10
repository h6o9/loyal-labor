<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'currently_available')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('currently_available')->default(true)->after('is_verified');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'currently_available')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('currently_available');
            });
        }
    }
};
