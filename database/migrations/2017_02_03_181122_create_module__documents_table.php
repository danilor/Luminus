<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_documents', function (Blueprint $table) {
            $table -> bigIncrements('id');
            $table -> string("module");
            $table -> string("name");
            $table -> string("key");
            $table -> string("mime");
            $table -> string("extension");
            $table -> string("real_path",2048);
            $table -> tinyInteger('active')->default(1);
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
        Schema::dropIfExists('module_documents');
    }
}
