<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'latitude')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('latitude', 10, 8)->nullable()->after('address');
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            });
        }

        if (!Schema::hasTable('technician_reviews')) {
            Schema::create('technician_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
                $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->json('tags')->nullable();
                $table->boolean('is_anonymous')->default(false);
                $table->boolean('is_approved')->default(true);
                $table->timestamps();

                $table->unique(['booking_id', 'customer_id']);
            });
        }

        if (!Schema::hasTable('app_notifications')) {
            Schema::create('app_notifications', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('body');
                $table->string('target_audience')->default('all'); // all, customers, technicians
                $table->json('payload')->nullable();
                $table->string('status')->default('draft'); // draft, queued, sent, failed
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('user_notifications')) {
            Schema::create('user_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('app_notification_id')->nullable()->constrained('app_notifications')->nullOnDelete();
                $table->string('title');
                $table->text('body');
                $table->string('type')->default('general');
                $table->json('data')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->string('push_status')->nullable(); // pending, sent, failed, skipped
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('app_notifications');
        Schema::dropIfExists('technician_reviews');

        if (Schema::hasColumn('users', 'latitude')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['latitude', 'longitude']);
            });
        }
    }
};
