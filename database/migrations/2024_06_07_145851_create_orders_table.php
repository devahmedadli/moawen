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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->boolean('rating_given')->default(false);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'canceled', 'rejected', 'accepted'])->default('pending');
            $table->date('deadline');
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
