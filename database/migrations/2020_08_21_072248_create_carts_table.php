<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('product_id')->unsigned()->nullable();
			$table->bigInteger('user_id')->unsigned()->nullable();
			$table->integer('table_id')->nullable();
			$table->string('order_type')->nullable();
			$table->string('token')->nullable();
			$table->string('title')->nullable();
			$table->tinyInteger('quantity')->length(3)->nullable();
			$table->decimal('price',10,2)->nullable();
			$table->decimal('total',10,2)->nullable();
			$table->string('date');
			$table->timestamps();

			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}