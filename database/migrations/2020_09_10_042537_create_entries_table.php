<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            // $table->integer('entry_status');
            $table->timestamps();
            $table->softDeletes();
            
            // $table->foreign('user_id')
            // ->references('id')->on('users')
            // ->onDelete('cascade');

            // $table->foreign('store_id')
            // ->references('id')->on('stores')
            // ->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('store_id')->references('id')->on('users');

            // $table->foreign('store_id')->references('id')->on('stores');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
