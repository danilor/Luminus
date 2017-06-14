<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("module");
            $table->string("method");
            $table->string('type');
            $table->integer('year');
            $table->integer('month');
            $table->integer('day');
            $table->integer('hour');
            $table->integer('minute');
            $table->dateTime('last_execution')->nullable();
            $table->dateTime('next_execution')->nullable();
            $table->tinyInteger("status")->default(1);
            $table->tinyInteger("in_progress")->default(0);
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
        Schema::dropIfExists('module_tasks');
    }
}
