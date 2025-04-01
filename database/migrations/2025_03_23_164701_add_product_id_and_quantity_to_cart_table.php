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
        Schema::table('cart', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('user_id'); // Add product_id column
            $table->integer('quantity')->default(1)->after('product_id'); // Add quantity column
            $table->foreign('product_id')->references('product_id')->on('product')->onDelete('cascade'); // Add foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // Drop foreign key constraint
            $table->dropColumn('product_id'); // Drop product_id column
            $table->dropColumn('quantity'); // Drop quantity column
        });
    }
};