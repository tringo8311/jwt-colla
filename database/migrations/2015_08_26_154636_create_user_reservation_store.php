<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserReservationStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_reservation_store', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('store_id');
            $table->string('prefer')->nullable();
            $table->dateTime('datetime');
            $table->string('content')->nullable();
            $table->enum("status",["pending","reject","accept","terminate"])->default('pending');
            $table->string('answer')->nullable();
            $table->integer('reminder')->nullable();
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
        Schema::drop('user_reservation_store');
    }
}
