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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // User who created the review - correctly references 'users' table
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            // Product being reviewed - make sure this matches your products table
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')
                  ->references('product_id')
                  ->on('product')  // Change this if your table is named differently
                  ->onDelete('cascade');
            
            // Order that contained the product - make sure this matches your orders table
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')    // Change this if your table is named differently
                  ->onDelete('cascade');
            
            // Rating (1-5)
            $table->unsignedTinyInteger('rating')
                  ->default(1);
            
            // Review comment
            $table->text('comment')
                  ->nullable();
            
            $table->timestamps();
            
            // Ensure one review per user per product per order
            $table->unique(['user_id', 'product_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};