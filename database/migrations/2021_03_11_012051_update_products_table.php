<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('delivery_type',['delivery','pickup'])->default('delivery')->after('type');
            $table->enum('is_taxable',['yes','no'])->default('no')->after('delivery_type');
            $table->enum('choice',['veg','nonveg','egg','vegan'])->default('veg')->after('is_taxable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
