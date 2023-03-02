<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('table_number')->unsigned();
            $table->string('qr')->nullable();
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->dateTime('delete_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tables_translations', function(Blueprint $table){
          $table->bigIncrements('id');
          $table->bigInteger('table_id')->unsigned();
          $table->string('title')->nullable();
          $table->string('locale')->index();
          $table->unique(['table_id','locale']);
          $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables');
    }
}
