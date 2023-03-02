<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('addon_group_id')->nullable()->unsigned();
            $table->decimal('price',8,2)->default(0);
            $table->enum('choice',['veg','nonveg','egg','vegan'])->default('veg');
            $table->string('status')->default('active');
            $table->timestamps();

            $table->foreign('addon_group_id')->references('id')->on('addon_groups')->onDelete('cascade');
        });
        Schema::create('addon_translations', function(Blueprint $table){
            $table->increments('id');
            $table->bigInteger('addon_id')->nullable()->unsigned();
            $table->string('title', 100)->nullable();
            $table->string('locale')->index();
            $table->unique(['addon_id','locale']);
            
            $table->foreign('addon_id')->references('id')->on('addons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addons');
    }
}
