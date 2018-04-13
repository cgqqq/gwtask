<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserUpdatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_updating', function (Blueprint $table) {
            $table->string('updating_id',32);
            $table->string('updater_id',20);
            $table->integer('time',false);
            $table->string('content',10000);
            $table->string('resource',100)->default(null)->nullable();
            $table->enum('type',['cTeam','sResource','pUpdating']);
            $table->primary('updating_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_updating');
    }
}
