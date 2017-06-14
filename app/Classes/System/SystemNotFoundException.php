<?php


namespace App\Classes\System;
use App\Classes\Common;
use Exception;


/**
 * Class SystemNotFoundException
 * This is for the cases when  we want to throw a 404 error inside the modules. Nothing else.
 *
 * Esta clase existe para los casos donde queremos tirar un error 404 dentro de los módulos. Nada más.
 *
 * @package App\Classes\System
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class SystemNotFoundException extends \Exception
{

    /**
     * SystemNotFoundException constructor.
     *
     * We are overwriting the constructor of this exception so we can add the log404 registry here
     *
     * Estamos sobreescribiendo el constructor de esta excepción así que podamos añadir el registro de log404 acá
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
            $l = new \App\Log404();
            $l -> url = Common::getRealUrl();
            $l -> request = serialize($_REQUEST);
            $l -> reason = $message;
            $l -> session_id = \Session::getId();
            $l -> save();
        parent::__construct($message, $code, $previous);
    }


}