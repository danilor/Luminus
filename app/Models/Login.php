<?php

declare(strict_types=1);
namespace App;
use App\Classes\ExtendedModel;


class Login extends ExtendedModel
{
    //

    /**
     * This method will create and save a new login information.
     * @param int $user_id
     * @param string $extra
     * @return int
     */
    public static function createNewLog(int $user_id, string $extra ) : int
    {
        $l = new Login;
        $l -> user_id = $user_id;
        $l -> session_id = \Session::getId();
        $l -> extra = $extra;
        $l -> ip = \Request::ip( true );
        $l -> save();
        return $l -> id;
    }

}
