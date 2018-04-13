<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaskSubmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stask_submission', function (Blueprint $table) {
            $table->string('submission_id',32);
            $table->string('stask_id',32);
            $table->integer('time',false);
            $table->string('score',100)->default(null)->nullable();
            $table->string('file',100)->default(null)->nullable();
            $table->primary('submission_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stask_submission');
    }
}
