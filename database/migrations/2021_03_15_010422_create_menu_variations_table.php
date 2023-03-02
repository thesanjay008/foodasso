<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_variations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('menu_id')->nullable()->unsigned();
            $table->bigInteger('variation_id')->nullable()->unsigned();
            $table->decimal('price',10,2)->nullable();
            $table->timestamps();
            $table->foreign('menu_id')->references('id')->on('products')->onDelete('cascade');
            //$table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_variations');
    }
}
