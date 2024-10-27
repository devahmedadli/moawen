<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name', 200);
            $table->text('description');
            $table->string('service_level', 100);
            $table->string('response_time', 100);
            $table->string('tags', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->string('delivery_time', 100);
            $table->string('thumbnail_url', 255)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('average_rating', 1, 1)->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
