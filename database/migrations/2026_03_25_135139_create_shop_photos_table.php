<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopPhotosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_photos', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to shops table
            $table->foreignId('shop_id')
                  ->constrained('shops')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            // Photo path (where the image is stored)
            $table->string('photo_path');
            
            // Is this the primary/cover photo?
            $table->boolean('is_primary')->default(false);
            
            // Optional: Photo metadata
            $table->string('photo_name')->nullable();
            $table->string('photo_size')->nullable();
            $table->string('mime_type')->nullable();
            
            // Optional: Photo order for display
            $table->integer('display_order')->default(0);
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('shop_id');
            $table->index('is_primary');
            $table->index('display_order');
            
            // Composite index for common queries
            $table->index(['shop_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_photos');
    }
}