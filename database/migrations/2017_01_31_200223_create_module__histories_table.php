<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_history', function (Blueprint $table) {
            $table->engine = config('database.default_engine');
            $table -> bigIncrements('id');
            $table -> string('module');
            $table -> float('version');
            $table -> string('action');
            $table -> string('description') -> nullable();
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_history');
    }
}
