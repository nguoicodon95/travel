<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned()->nullable()->default(0);
            $table->foreign('post_id')->references('id')->on('posts');
            $table->string('fullname');
            $table->enum('gender', ['mr', 'ms', 'mrs']);
            $table->string('address');
            $table->string('email', 100);
            $table->string('phone', 15);
            $table->date('start_date');
            $table->string('travel_time');
            $table->tinyInteger('number_person');
            $table->tinyInteger('number_children');
            $table->string('activity_type');
            $table->string('travel_type');
            $table->string('eat_type');
            $table->mediumText('content');
            $table->string('data');
            $table->tinyInteger('status')->default(0);
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
        Schema::drop('bookings');
    }
}
