<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->engine = config('database.default_engine');
            $table->bigIncrements('id');
            $table->string('username',150)->unique();
            $table->string('password');
            $table->string('identification')->nullable(  ); // In case of CR, the "c�dula"
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email',150)->unique(  ); // This should be the institutional email
            $table->string('personal_email')->nullable(  );
            $table->string('known_as')->nullable(  );
            $table->date('born')->nullable(  );
            $table->string('main_phone')->nullable(  );
            $table->string('secondary_phone')->nullable(  );
            $table->string('internal_phone_extension')->nullable(  );
            $table->string('gender')->nullable(  );
            $table->string('address');
            $table->string('address2')->nullable(  );
            $table->string('civil_status')->nullable(  );
            $table->string('photo')->nullable(  );
            $table->longText('notes')->nullable(  );
            $table->tinyInteger("status")->default( 1 );
            $table->tinyInteger("administrator")->default( 0 );
            $table->rememberToken(  );
            $table->timestamps(  );
            $table->dateTime("lastLogin")->nullable();
            $table->softDeletes(  );
            $table->index("username");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
