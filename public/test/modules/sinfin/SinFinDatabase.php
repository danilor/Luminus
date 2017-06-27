<?php

namespace Modules;


use App\Classes\DModule\Database\DatabaseConnection;

class SinFinDatabase
{
    const HOST = '190.184.198.15';
    const PORT = '3306';
    const DATABASE = 'globalexcrm';
    const USERNAME = 'root';
    const PASSWORD = 'MySqlSrvRack2016';
    const DRIVER  = 'mysql';

    public static function getSinfinConnection() : DatabaseConnection
    {
        $db = new DatabaseConnection( self::HOST , self::PORT , self::DRIVER , self::DATABASE , self::USERNAME , self::PASSWORD );
        return $db;
    }

}