<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->string('task_id',32);
            $table->string('task_name',30);
            $table->string('task_team_id',32);
            $table->string('task_status',10);
            $table->string('task_description',1000);
            $table->string('task_manager_id',32);
            $table->integer('task_deadline',false);
            $table->integer('task_kickoff_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task');
    }
}
