<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->string('link');
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->integer('sort')->default(0);
            $table->unsignedBigInteger('menu_id');
            $table->boolean('custom_item')->default(0);
            $table->boolean('open_new_tab')->default(0);
            $table->timestamps();

            $table->foreign('menu_id')->references('id')
                ->on('menus')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
};
