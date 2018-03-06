<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaskAllocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stask_allocation', function (Blueprint $table) {
            //唯一ID
            $table->string('a_id',32);
            //子任务ID
            $table->string('stask_id',32);
            //负责人ID
            $table->string('res_id',32);
            $table->primary('a_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stask_allocation');
    }
}
