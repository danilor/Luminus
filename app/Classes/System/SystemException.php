<?php

declare(strict_types=1);

namespace App\Classes\System;
use Exception;

/**
 * Class SystemException
 *
 * This class should take over some of the site exceptions
 *
 * Esta clase va a tomar poseción sobre algunos de las excepciones del sistema
 *
 * @package App\Classes\System
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class SystemException extends \Exception
{

    /**
     * SystemException constructor.
     * We are overwriting the constructor
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $exception = new \App\Exception();
        $exception -> message = $message;
        $exception -> code = (int)$code;
        $exception -> type = 'exception';
        $exception -> trace = $this->getTraceAsString();
        $exception -> save();
    }

}