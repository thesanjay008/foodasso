<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_category', function (Blueprint $table) {
            $table->increments('id');
			$table->bigInteger('owner_id')->nullable()->unsigned();
            $table->string('priority')->nullable();
            $table->string('image');
            $table->string('status')->default('active');
            $table->timestamps();
			
			$table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('menu_category_translations', function(Blueprint $table){
            $table->increments('id');
            $table->bigInteger('menu_category_id')->nullable()->unsigned();
            $table->string('title')->nullable();
            $table->string('locale')->index();
            $table->unique(['menu_category_id','locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_category');
    }
}
