<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_offers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('store_id');
            $table->float('off')->default(0);;
            $table->enum('off_type', ['%', '$'])->default('%');;
            $table->float('off_max');
            $table->string('file_url');
            $table->string('subject');
            $table->string('content');
            $table->dateTime("start_time");
            $table->dateTime("end_time");
            $table->boolean("activated")->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_offers', function (Blueprint $table) {
            //
        });
    }
}
