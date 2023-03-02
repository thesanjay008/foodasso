<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
			$table->string('mobile_number')->unique()->nullable();
			$table->string('profile_image')->nullable();
            $table->string('password');
            $table->string('user_type');
			$table->enum('gender', ['Male','Female','Other'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
			$table->string('added_by_id')->nullable();
			$table->enum('is_subscribed',['Yes','No'])->default('No');
			$table->tinyInteger('noti_via_nitification')->default('1');
            $table->tinyInteger('noti_via_email')->default('1');
            $table->enum('status',['active','inactive','pending','blocked'])->default('pending');
			$table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
