<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("to",1056)->nullable();
            $table->string("cc",1056)->nullable();
            $table->string("bcc",1056)->nullable();
            $table->string("from_email",1056);
            $table->string("from_name",1056);
            $table->string("template",128);
            $table->string("subject",1056);
            $table->longText("body");
            $table->boolean('individual')->default(0)->comment('If is true, then we have to mark each email individually');
            $table->integer('status')->default(0);
            $table->integer('sent')->default(0);
            $table->dateTime('sent_date')->nullable();
            $table->text('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
}
