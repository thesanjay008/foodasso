<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('modules_type')->nullable();
            $table->string('image')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });
        Schema::create('categories_translations', function(Blueprint $table){
          $table->increments('id');
          $table->integer('cat_id')->unsigned();
          $table->string('title')->nullable();
          $table->text('description')->nullable();
          $table->string('locale')->index();
          $table->unique(['cat_id','locale']);
          $table->foreign('cat_id')->references('id')->on('categories')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
