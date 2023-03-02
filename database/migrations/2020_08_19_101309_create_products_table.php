<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('owner_id')->nullable()->unsigned();
			$table->integer('menu_category_id')->length(11)->nullable();
            $table->string('image')->nullable();
            $table->string('quantity')->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->string('date')->nullable();
            $table->string('type')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
			
			$table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
		
		Schema::create('products_translations', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('locale')->index();
            $table->unique(['product_id','locale']);
			
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
        Schema::dropIfExists('products');
    }
}
