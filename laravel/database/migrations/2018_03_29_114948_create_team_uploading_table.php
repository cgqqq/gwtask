<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamUploadingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_uploading', function (Blueprint $table) {
            $table->string('id',32);
            $table->string('team_id',32);
            $table->string('uploader_id',20);
            $table->integer('time',false);
            $table->string('content',10000);
            $table->string('resource',100)->default(null)->nullable();;
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_uploading');
    }
}
