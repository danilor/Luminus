<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLog404sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log404s', function (Blueprint $table) {
            $table -> bigIncrements('id');
            $table -> string('url' , 1048);
            $table -> longText('request')->nullable();
            $table -> string('reason') -> nullable();
            $table -> string('session_id') -> nullable();
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
        Schema::dropIfExists('log404s');
    }
}
