<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail', function (Blueprint $table) {
            $table->string('mail_id',32);
            $table->string('mail_from_id',32);
            $table->string('mail_to_id',20);
            $table->integer('mail_sent_time',false);
            $table->string('mail_title',100);
            $table->string('mail_content',10000);
            $table->enum('mail_status',['0','1']);/*0 unread 1 read*/
            $table->enum('mail_type',['0','1']);/*0 sent by system 1 sent by user*/
            $table->primary('mail_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail');
    }
}
