<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('title')->nullable();
            $table->bigInteger('city_id')->unsigned();
			$table->integer('postal_code')->nullable();
			$table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
			$table->decimal('delivery_charges',10,2)->default(0);
            $table->enum('status',['active','inactive'])->default('inactive');
            $table->timestamps();
			
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
	*/
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}