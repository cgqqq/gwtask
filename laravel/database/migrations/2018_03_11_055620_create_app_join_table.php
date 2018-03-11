<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppJoinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_join', function (Blueprint $table) {
            $table->string('team_id',32);
            $table->string('applicant_id',32);
            $table->string('app_team_id',32);
            $table->enum('status',['0','1','2']);
            $table->primary('team_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_join');
    }
}
