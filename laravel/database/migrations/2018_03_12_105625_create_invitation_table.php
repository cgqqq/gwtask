<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invitation', function (Blueprint $table) {
            $table->string('invitation_id',32);
            $table->string('team_id',32);
            $table->string('user_id',20);
            $table->integer('time',false);
            $table->string('title',100);
            $table->string('content',10000);
            $table->primary('invitation_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitation');
    }
}
