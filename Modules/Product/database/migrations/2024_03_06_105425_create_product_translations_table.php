<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\app\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignIdFor(Product::class)->constrained()->onDelete('cascade')->unique();
            $table->string('lang_code');
            $table->string('name');
            $table->longText('description');
            $table->text('short_description')->nullable();
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_translations');
    }
};
