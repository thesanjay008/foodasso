<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('code')->nullable();
            $table->string('image')->nullable();
			$table->text('description')->nullable();
            $table->decimal('discount')->default(0);
            $table->enum('discount_type',['amount','percentage'])->default('percentage');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('coupons');
    }
}
