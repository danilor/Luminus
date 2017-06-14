<?php

namespace Modules;


use App\Classes\DModule\Database\DatabaseConnection;

class GlobalexSite
{
    const HOST = '190.184.198.8';
    const PORT = '3306';
    const DATABASE = 'globalexnoticias';
    const USERNAME = 'root';
    const PASSWORD = 'ldazGlbSI2010';
    const DRIVER  = 'mysql';

    public static function getConnection() : DatabaseConnection
    {
        $db = new DatabaseConnection( self::HOST , self::PORT , self::DRIVER , self::DATABASE , self::USERNAME , self::PASSWORD );
        return $db;
    }

}