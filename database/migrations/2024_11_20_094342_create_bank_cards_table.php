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
        Schema::create('bank_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Payment processor tokens
            $table->string('processor_token');        // Token from Stripe/PayPal etc.
            $table->string('processor_card_id');      // Card ID from processor
            $table->string('processor_name');         // 'stripe', 'paypal', etc.
            
            // Non-sensitive display data
            $table->string('last_four_digits', 4);
            $table->string('card_brand');             // visa, mastercard, etc.
            $table->string('card_holder_name');
            $table->string('expiry_month', 2);
            $table->string('expiry_year', 4);
            
            // Add indexes for common queries
            $table->index(['user_id', 'is_default']);
            $table->index(['last_four_digits']);
            $table->index(['created_at']);
            
            // Card metadata
            $table->string('card_type');
            $table->string('card_issuer');
            $table->string('card_country');
            $table->string('card_currency');
            
            // Status flags
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_cards');
    }
};
