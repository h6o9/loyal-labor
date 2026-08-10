<?php
// Create migration for draft shops
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDraftShopsTable extends Migration
{
    public function up()
    {
        Schema::create('draft_shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->string('shop_name')->nullable();
            $table->string('category')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('address')->nullable();
            $table->text('about_shop')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('current_step')->default(1);
            $table->enum('status', ['draft', 'completed'])->default('draft');
            $table->timestamps();

            // Only one draft per staff
            $table->unique('staff_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('draft_shops');
    }
}