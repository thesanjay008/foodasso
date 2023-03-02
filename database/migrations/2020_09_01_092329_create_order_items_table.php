<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->unsigned()->nullable();
            $table->integer('custom_order_id')->unsigned()->nullable();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->tinyInteger('quantity')->length(3)->nullable();
			$table->decimal('price',10,2)->nullable();
			$table->decimal('total',10,2)->nullable();
            $table->timestamps();
			
            //$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            //$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_items');
    }
}
