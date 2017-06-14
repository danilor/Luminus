<?php
declare(strict_types=1);

namespace App\Classes\DModule\Tasks;


/**
 * Class Schedule
 *
 * This class will manage the schedule times to be read by the crontab autoprocess.
 *
 * Esta clase va a manera las tareas programadas para ser leídas por el proceso automático del crontab
 *
 * @package App\Classes\DModule\Tasks
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class Schedule
{
    private $year = 0 , $month = 0 , $day = 0 , $hour = 0 , $minute = 0;
    /**
     * This indicates the type of frequency we want to use.
     * Posible values are:
     * -f normal frequency (each X hours for example)
     * -t timed frecuency (all days at 2 pm for example)
     *
     * Esta variable indica la frecuencia que queremos usar. Los posibles valores son:
     * -f frecuencia normal (cada X horas por ejemplo)
     * -t por tiempo (todos los días a las 2 de la tarde por ejemplo)
     *
     * @var string
     */
    private $type = "";

    /**
     * Schedule constructor.
     */
    public function __construct()
    {
    }


    /**
     * This method will clean all the schedule values
     *
     * Este método va a limpiar todos los valores de la tarea programada
     *
     */
    private function clean(){
        $this -> year = $this -> month = $this -> day = $this -> hour = $this -> minute = 0;
        $this -> type = "";
    }


    /**
     * This sets up the frecuency for normal tasks (day, hour and minute)
     *
     * Esto establce la frecuencia para una tarea normal (día, hora, minuto)
     *
     * @param int $dat
     * @param int $hour
     * @param int $minute
     */
    public function setUpFrequency(int $day , int $hour , int $minute ){
        $this -> clean();
        $this -> day = $day;
        $this -> hour = $hour;
        $this -> minute = $minute;
        $this -> type = "f";
    }

    /**
     * This sets the timed frequency. This is for example: executes every day at 12 pm.
     *
     * Esto establece la frecuencia de tiempo. Por ejemplo, executar todos los días al medio día.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     */
    public function setTimedFrequency(int $year , int $month , int $day , int $hour , int $minute ){
        $this -> year   =   $year;
        $this -> month  =   $month;
        $this -> day    =   $day;
        $this -> hour   =   $hour;
        $this -> minute =   $minute;
        $this -> type   =   "t";
    }

    /**
     * Gets the year
     *
     * Obtiene el año
     *
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * Gets the month
     *
     * Obtiene el mes
     *
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * Gets the day
     *
     * Obtiene el día
     *
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * Gets the hour
     *
     * Obtiene la hora
     *
     * @return int
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * Gets the minute
     *
     * Obtiene los minutos
     *
     * @return int
     */
    public function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * Gets the type
     *
     * Obtiene el tipo
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }



}