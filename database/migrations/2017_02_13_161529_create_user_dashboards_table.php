<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDashboardsTable extends Migration
{
    public function up()
    {
        Schema::create( 'user_dashboard' , function (Blueprint $table) {
            $table->bigIncrements( 'id' );
            $table->bigInteger('user_id')->unsigned();
            $table->string( 'label' , 128 )->default('');
            $table->string( 'module' , 128 );
            $table->string( 'method' , 128 ) ;
            $table->integer( 'order' )->default( 0 );
            $table->string( 'side' , 10 )->default( 'l' ); // l = left | r = right
            $table->boolean( 'status' )->default( 1 );
            $table->timestamps();
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
        });
    }
    public function down()
    {
        Schema::dropIfExists('user_dashboard');
    }
}
