<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('owner_id')->unsigned();
            $table->string('image',255)->nullable();
            $table->string('banner_image',255)->nullable();
            $table->string('slug',255)->nullable();
            $table->string('phone_number',15)->nullable();
            $table->string('email',99)->nullable();
            $table->bigInteger('flat_discount')->length(11)->nullable();
            $table->string('start_time',11)->nullable();
            $table->string('end_time',11)->nullable();
            $table->bigInteger('country')->length(11)->nullable();
            $table->bigInteger('state')->length(11)->nullable();
            $table->bigInteger('city')->length(11)->nullable();
            $table->bigInteger('zip_code')->length(11)->nullable();
            $table->string('latitude',21)->nullable();
            $table->string('longitude',21)->nullable();
            $table->enum('status',['Active','Inactive','Closed','PickupOnly'])->default('Active');
            $table->dateTime('delete_at')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('outlets_translations', function(Blueprint $table){
          $table->bigIncrements('id');
          $table->bigInteger('outlet_id')->unsigned();
          $table->string('title')->nullable();
          $table->text('description')->nullable();
          $table->text('area')->nullable();
          $table->text('address')->nullable();
          $table->string('locale')->index();
          $table->unique(['outlet_id','locale']);
          $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outlets');
    }
}
