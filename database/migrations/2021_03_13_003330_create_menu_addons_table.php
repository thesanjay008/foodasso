<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_addons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('menu_id')->nullable()->unsigned();
            $table->bigInteger('addon_group_id')->nullable()->unsigned();
            $table->timestamps();
            $table->foreign('menu_id')->references('id')->on('products')->onDelete('cascade');
            //$table->foreign('addon_group_id')->references('id')->on('addon_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_addons');
    }
}
