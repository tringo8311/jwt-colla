<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stores', function(Blueprint $table) {
            //$table->increments('id');
            $table->integer('store_id')->unsigned()->index();
            //$table->foreign('store_id')->references('bID')->on('stores')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            //$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['store_id', 'user_id']);
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
        Schema::drop('users_store');
    }
}
