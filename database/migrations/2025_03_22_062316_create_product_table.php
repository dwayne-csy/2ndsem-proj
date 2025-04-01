<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id('product_id'); // Auto-incrementing primary key
            $table->string('product_name');
            $table->enum('size', ['XS', 'S', 'M', 'L', 'XL', 'XXL']);
            $table->enum('category', ['Mens', 'Womens', 'Kids']);
            $table->enum('types', ['T-shirt', 'Polo Shirt', 'Sweater', 'Hoodie', 'Jersey', 'Dress', 'Sweatshirt', 'Pants', 'Shorts']);
            $table->text('description');
            $table->decimal('cost_price', 10, 2); // Decimal with 10 digits, 2 decimal places
            $table->decimal('sell_price', 10, 2); // Decimal with 10 digits, 2 decimal places
            $table->integer('stock');
            $table->timestamps(); // Adds `created_at` and `updated_at` columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }
};