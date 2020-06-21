<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('schedule_id');
            $table->smallInteger('status')->default(0);
            $table->string('landmark_address');
            //1 pending 2 approved  3 completed 4 rejected 5 cancel
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();

            $table->time('sched_start_time')->nullable();
            $table->time('sched_end_time')->nullable();
            $table->unsignedBigInteger('day_id');

            $table->string('feedback')->nullable();
            $table->integer('rating')->nullable();
            $table->string('feedback_client')->nullable();
            $table->integer('rating_client')->nullable();
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
        Schema::dropIfExists('client_schedules');
    }
}
