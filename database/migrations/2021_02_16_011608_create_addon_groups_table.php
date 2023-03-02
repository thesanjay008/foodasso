<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addon_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->default('active');
            $table->timestamps();
        });
        Schema::create('addon_group_translations', function(Blueprint $table){
            $table->increments('id');
            $table->bigInteger('addon_group_id')->nullable()->unsigned();
            $table->string('title', 100)->nullable();
            $table->string('description', 100)->nullable();
            $table->string('locale')->index();
            $table->unique(['addon_group_id','locale']);
            
            $table->foreign('addon_group_id')->references('id')->on('addon_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addon_group_translations');
        Schema::dropIfExists('addon_groups');
    }
}
