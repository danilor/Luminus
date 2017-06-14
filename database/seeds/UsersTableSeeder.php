<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $born = new \DateTime();
        $born -> setDate( 1989 , 03 , 20 );
        $born -> setTime( 0 , 0 , 0 );
        $dated = new \DateTime();
        DB::table('users')->insert([
            "username"              =>              "danilor",
            "password"              =>              bcrypt("123456"),
            "identification"        =>              "0000000000",
            "firstname"             =>              "Danilo",
            "lastname"              =>              "Ramírez",
            "email"                 =>              "daniloramirez.cr@gmail.com",
            "personal_email"        =>              "daniloramirez.cr@gmail.com",
            "known_as"              =>              "",
            "born"                  =>              $born,
            "main_phone"            =>              "88975131",
            "secondary_phone"       =>              "",
            "internal_phone_extension" =>           "",
            "gender"                =>              "M",
            "address"               =>              "Desamparados",
            "address2"              =>              "Gravilias",
            "administrator"         =>              1,
            "created_at"            =>              $dated,
            "updated_at"            =>              $dated,
            "photo"                 =>              'avatar01.jpg'
        ]);


        $jobs = [
            ["name"=>"programador","label"=>"Programador"],
            ["name"=>"tecnico","label"=>"Técnico"],
            ["name"=>"gerente","label"=>"Gerente"],
            ["name"=>"editor","label"=>"Editor"],
            ["name"=>"vendedor","label"=>"Vendedor"],
        ];

        foreach( $jobs AS $job){
            DB::table('jobs')->insert([
                "name"              =>              $job["name"],
                "label"             =>              $job["label"],
                "created_at"        =>              $dated,
                "updated_at"        =>              $dated,
            ]);
        }


    }
}
