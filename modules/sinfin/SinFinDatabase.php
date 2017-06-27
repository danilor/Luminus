<?php

namespace Modules;


use App\Classes\DModule\Database\DatabaseConnection;

class SinFinDatabase
{
    const HOST = '';
    const PORT = '';
    const DATABASE = '';
    const USERNAME = '';
    const PASSWORD = '';
    const DRIVER  = '';

    public static function getSinfinConnection() : DatabaseConnection
    {
        $db = new DatabaseConnection( self::HOST , self::PORT , self::DRIVER , self::DATABASE , self::USERNAME , self::PASSWORD );
        return $db;
    }

}
