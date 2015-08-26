<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_feedbacks', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('rate');
            $table->string('service');
            $table->string('employee');
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
        Schema::drop('users_feedback');
    }
}
