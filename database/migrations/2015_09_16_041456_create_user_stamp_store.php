<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserStampStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stamp_store', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_id');
            $table->integer('user_id');
            $table->integer('store_id');
            $table->dateTime('datetime');
            $table->float('paid');
            $table->boolean("used")->default(0);
            $table->string('content');
            $table->string('ip');
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
        /*Schema::table('user_stamp_store', function (Blueprint $table) {
            //
        });*/
        Schema::drop('user_stamp_store');
    }
}
