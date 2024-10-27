<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->string('title', 100);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('delivery_time');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_packages');
    }
};
