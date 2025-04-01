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
        Schema::table('product', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable()->after('stock'); 
            
            // Ensure the correct foreign key reference
            $table->foreign('supplier_id')->references('supplier_id')->on('supplier')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']); // Correct way to drop foreign key
            $table->dropColumn('supplier_id'); // Drop the column
        });
    }
    
    
};
