<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('email_id')->unsigned();
            $table->string('path',2048);
            $table->string("name",512)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('email_id')->references('id')->on('emails');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_attachments');
    }
}
