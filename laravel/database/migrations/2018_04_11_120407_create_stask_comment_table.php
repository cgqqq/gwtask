<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaskCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stask_comment', function (Blueprint $table) {
            $table->string('id',32);
            $table->string('stask_id',32);
            $table->integer('time',false);
            $table->string('commentator_id',32);
            $table->string('comment',1000);
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
        Schema::dropIfExists('stask_comment');
    }
}
