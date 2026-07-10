<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();

            // Donor details
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->text('billing_address');

            // Declarations
            $table->boolean('gift_aid')->default(false);
            $table->boolean('on_behalf_of_organisation')->default(false);
            $table->boolean('cover_fee')->default(false);

            // Basket + money
            $table->json('items');
            $table->string('currency', 8)->default('GBP');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
