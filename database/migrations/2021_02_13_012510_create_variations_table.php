<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('group_id')->nullable()->unsigned();
            $table->string('status')->default('active');
            $table->timestamps();
			
			//$table->foreign('group_id')->references('id')->on('variation_groups')->onDelete('cascade');
        });
        Schema::create('variation_translations', function(Blueprint $table){
            $table->increments('id');
            $table->bigInteger('variation_id')->nullable()->unsigned();
            $table->string('title', 100)->nullable();
            $table->string('description', 100)->nullable();
            $table->string('locale')->index();
            $table->unique(['variation_id','locale']);
            
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variation_translations');
        Schema::dropIfExists('variations');
    }
}
