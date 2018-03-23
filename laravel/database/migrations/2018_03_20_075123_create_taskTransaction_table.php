<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taskTransaction', function (Blueprint $table) {
            $table->string('tran_id',32);
            $table->string('task_id',32);
            $table->string('trans_brief',100);
            $table->string('trans_description',10000);
            $table->string('trans_Resource_path',100)->default(null)->nullable();
            $table->string('trans_Resource_intro',1000)->default(null)->nullable();
            $table->integer('time',false);
            $table->primary('tran_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taskTransaction');
    }
}

