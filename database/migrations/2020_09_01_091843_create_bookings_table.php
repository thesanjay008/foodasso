<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('custom_order_id')->nullable();
			$table->bigInteger('user_id')->unsigned()->nullable();
			$table->string('token')->nullable();
			$table->string('name')->nullable();
			$table->string('email')->nullable();
			$table->string('phone_number')->nullable();
			$table->bigInteger('outlate_id')->nullable()->unsigned();
            $table->integer('table_id')->length(3)->nullable();
            $table->integer('coupon_id')->length(3)->nullable();
            $table->integer('offer_id')->length(3)->nullable();
            $table->tinyInteger('quantity')->length(3)->default(0);
            $table->decimal('tax',10,2)->default(0);
            $table->decimal('discount',10,2)->default(0);
            $table->decimal('total',10,2)->default(0);
            $table->decimal('grand_total',10,2)->default(0);
			$table->string('date')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
			$table->enum('payment_method',['COD','Online'])->nullable();
            $table->integer('payment_method_id')->length(11)->nullable();
            $table->string('payment_tracking_id')->nullable();
            $table->enum('process_completed', ['Yes','No'])->default('No');
            $table->enum('status',['Temporary','New','Accepted','Preparing','Dispatched','Out-For-Delivery','On-Hold','Completed','Rejected','Canceled','Failed'])->default('Temporary');
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
        Schema::dropIfExists('orders');
    }
}
