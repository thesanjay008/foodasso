<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('custom_order_id')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_number')->nullable();
            $table->bigInteger('address_id')->unsigned()->nullable();
            $table->string('address')->nullable();
            $table->integer('table_id')->length(3)->nullable();
            $table->integer('coupon_id')->length(3)->nullable();
            $table->integer('item_count')->length(11)->default(0);
            $table->tinyInteger('quantity')->length(3)->default(0);
            $table->decimal('tax',10,2)->default(0);
            $table->decimal('discount',10,2)->default(0);
            $table->decimal('delivery_fee',10,2)->default(0);
            $table->decimal('total',10,2)->default(0);
            $table->decimal('grand_total',10,2)->default(0);
			$table->bigInteger('owner_id')->nullable()->unsigned();
			$table->integer('restaurant_id')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_date',12)->nullable();
            $table->string('tracking_id')->nullable();
            $table->string('delivery_date',12)->nullable();
            $table->string('order_type')->nullable();
            $table->enum('payment_mode',['Cash','Online'])->nullable();
            $table->integer('payment_method_id')->length(11)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_order_id')->nullable();
            $table->string('order_date')->nullable();
            $table->enum('status',['Temporary','New','Preparing','Dispatched','Out-For-Delivery','On-Hold','Delivered','Canceled','Rejected','Failed'])->default('Temporary');
            $table->integer('goods_received')->length('2')->nullable();
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
