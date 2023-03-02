<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->default('active');
            $table->timestamps();
        });
        Schema::create('variation_group_translations', function(Blueprint $table){
            $table->increments('id');
            $table->bigInteger('variation_group_id')->nullable()->unsigned();
            $table->string('title', 100)->nullable();
            $table->string('description', 100)->nullable();
            $table->string('locale')->index();
            $table->unique(['variation_group_id','locale']);
            
            $table->foreign('variation_group_id')->references('id')->on('variation_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variation_group_translations');
        Schema::dropIfExists('variation_groups');
    }
}
