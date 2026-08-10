<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\PageBuilder\app\Models\CustomizeablePage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customizable_page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CustomizeablePage::class)->index();
            $table->string('lang_code')->index();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customizable_page_translations');
    }
};
