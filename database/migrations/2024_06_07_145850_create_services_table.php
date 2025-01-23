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
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name', 200);
            $table->text('description');
            $table->enum('service_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->string('response_time', 100)->nullable();
            $table->json('tags')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('delivery_time', 100);
            $table->string('thumbnail', 255)->nullable();
            $table->enum('status', ['pending', 'revision', 'approved', 'rejected', 'published', 'unpublished'])->default('pending');
            $table->decimal('average_rating', 3, 1)->nullable();
            $table->integer('views')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
