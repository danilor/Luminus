<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->engine = config('database.default_engine');
            $table -> bigIncrements('id');
            $table -> bigInteger("user_id") -> unsigned();
            $table -> string( 'session' ) -> nullable();
            $table -> string( 'url' ) -> nullable();
            $table -> string( "action" ) -> nullable();
            $table -> string( "description" ) -> nullable();

            $table -> longText( 'request' ) -> nullable();

            $table -> bigInteger( "external_id_1" )->default( 0 );
            $table -> bigInteger( "external_id_2" )->default( 0 );
            $table -> bigInteger( "external_id_3" )->default( 0 );
            $table -> bigInteger( "external_id_4" )->default( 0 );
            $table -> timestamps(  );
            $table -> foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
