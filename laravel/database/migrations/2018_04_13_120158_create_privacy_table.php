<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privacy', function (Blueprint $table) {
            $table->string('privacy_id',32);
            $table->string('user_id',32);
            $table->enum('view_page',['0','1']);
            $table->enum('download_resource',['0','1']);
            $table->enum('view_team_joined',['0','1']);
            $table->enum('view_team_created',['0','1']);
            $table->enum('view_task',['0','1']);
            $table->primary('privacy_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privacy');
    }
}
