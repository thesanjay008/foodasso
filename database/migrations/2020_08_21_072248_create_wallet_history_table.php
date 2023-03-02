<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_history', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('user_id')->unsigned()->nullable();
          $table->string('title')->nullable();
          $table->decimal('amount',10,2)->default(0);
          $table->decimal('balance',10,2)->default(0);
		  $table->enum('type',['Deposit','Withdraw'])->nullable();
		  $table->enum('status',['Pending','Complete','Failed'])->nullable();
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
        Schema::dropIfExists('wallet_history');
    }
}