<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCleanerSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cleaner_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cleaner_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedInteger('day_id')->nullable();
            $table->date('date')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->smallInteger('availability')->default(0);
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
        Schema::dropIfExists('cleaner_schedules');
    }
}
