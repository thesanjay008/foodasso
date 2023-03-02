<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('state_id');
            $table->integer('organization_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('category_id')->nullable();
            $table->string('license_id')->nullable();
            $table->string('bio')->nullable();
            $table->decimal('charges',10,2)->nullable();
            $table->string('rating')->nullable();
            $table->string('review')->nullable();
            $table->text('address')->nullable();
			$table->string('latitude')->nullable();
			$table->string('longitude')->nullable();
            $table->integer('status')->default('0')->nullable();
            $table->string('license')->nullable();
            $table->string('pancard')->nullable();
            $table->string('adharCard')->nullable();
            $table->string('adharcard_back')->nullable();
            $table->string('start_time')->nullable();
			$table->string('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_info');
    }
}
