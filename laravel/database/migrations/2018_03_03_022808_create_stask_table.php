<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stask', function (Blueprint $table) {
            $table->string('stask_id',32);
            $table->string('task_name',30);
            $table->string('task_description',1000);
            $table->string('task_id',32);
            $table->string('status',5);
            $table->primary('stask_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stask');
    }
}
