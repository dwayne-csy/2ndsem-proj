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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
            
            // Use the same column type as your users table's primary key
            $table->unsignedBigInteger('user_id');
            

            // Foreign key for users (references default 'id' column)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');


            
            // Prevent duplicate items in cart
            $table->unique(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::dropIfExists('cart');
    }
};