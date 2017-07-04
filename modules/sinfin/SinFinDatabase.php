<?php

namespace Modules;


use App\Classes\DModule\Database\DatabaseConnection;

class SinFinDatabase
{
<<<<<<< HEAD
    const HOST = '190.184.198.15';
    const PORT = '3306';
    const DATABASE = 'globalexcrm';
    const USERNAME = 'root';
    const PASSWORD = 'MySqlSrvRack2016';
    const DRIVER  = 'mysql';
=======
    const HOST = '';
    const PORT = '';
    const DATABASE = '';
    const USERNAME = '';
    const PASSWORD = '';
    const DRIVER  = '';
>>>>>>> 6b863e0bc133d0d99e6dad6e75359a257fff3c25

    public static function getSinfinConnection() : DatabaseConnection
    {
        $db = new DatabaseConnection( self::HOST , self::PORT , self::DRIVER , self::DATABASE , self::USERNAME , self::PASSWORD );
        return $db;
    }

<<<<<<< HEAD
}
=======
}
>>>>>>> 6b863e0bc133d0d99e6dad6e75359a257fff3c25
