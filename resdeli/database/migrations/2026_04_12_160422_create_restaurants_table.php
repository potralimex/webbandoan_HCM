<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('image')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('delivery_time')->default(30); // minutes
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('min_order', 8, 2)->default(0);
            $table->boolean('is_open')->default(true);
            $table->boolean('is_active')->default(true);
            $table->time('open_time')->default('08:00:00');
            $table->time('close_time')->default('22:00:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
