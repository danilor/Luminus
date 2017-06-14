<?php

namespace App\Classes\Crontab;


/**
 * Class Job
 * The job class represents the Job in the crontab
 * La clase "job" representa un trabajo en el crontab
 * @package App\Classes\Crontab
 * @see \App\Classes\Crontab\Crontab
 */
class Job
{

    /**
     * Indicates the route parameter to use
     * Indica cual es el parémtro de ruta para usar
     * @var string
     */
    const ROUTE_PARAMETER_APP       = '[R_APP]';
    const ROUTE_PARAMETER_STORAGE   = '[R_STO]';

    /**
     * @var string
     */
    private $command = '';

    /**
     * Per default, the frequency is every minute
     * Predeterminado, la frecuencia es cada minuto
     * @var string
     */
    private $frequency = '* * * * *';

    /**
     * Job constructor.
     * @param string $command
     * @param string $frequency
     */
    public function __construct($command = '', $frequency = '* * * * *')
    {
        $this -> command    =   $command;
        $this -> frequency  =   $frequency;
    }

    /**
     * It will automatically replace the PARAMETER APP for the actual route
     * @return string
     */
    public function getCommand(): string
    {
        $aux = $this -> command;
        $aux = str_replace( Job::ROUTE_PARAMETER_APP        ,   app_path()      , $aux );
        $aux = str_replace( Job::ROUTE_PARAMETER_STORAGE    ,   storage_path()  , $aux );
        return $aux;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getFrequency(): string
    {
        return $this->frequency;
    }

    /**
     * @param string $frequency
     */
    public function setFrequency(string $frequency)
    {
        $this->frequency = $frequency;
    }
}