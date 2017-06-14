<?php
declare(strict_types=1);
namespace App\Classes\System;

use Exception;

/**
 * Class SystemError
 *
 * This class will take over some of the system errors
 *
 * Esta clase va a tomar poseción sobre algunos de los errores del sistema
 *
 * @package App\Classes\System
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class SystemError extends \Error
{
    /**
     * SystemError constructor.
     * We are overwriting the Error constructor with ours
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $exception = new \App\Exception();
        $exception -> message = $message;
        $exception -> code = (int)$code;
        $exception -> type = 'error';
        $exception -> trace = $this->getTraceAsString();
        $exception -> save();
    }

}