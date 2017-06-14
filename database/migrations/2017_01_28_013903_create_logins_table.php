<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logins', function (Blueprint $table) {
            $table->engine = config('database.default_engine');
            $table->bigIncrements('id');
            $table->bigInteger("user_id") -> unsigned();
            $table->string("session_id");
            $table->text("extra")->nullable();
            $table->string("ip")->nullable();
            $table->tinyInteger("active")->default( 1 ); //As long as the registry has the active status, it should remain active.
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logins');
    }
}
