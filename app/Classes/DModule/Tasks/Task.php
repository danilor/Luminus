<?php
namespace App\Classes\DModule\Tasks;


/**
 * Class Task
 *
 * This class is the object type for the Task. It will have a schedule frequency by default.
 * It will have set up to be executed each day by default.
 *
 * Esta clase es el tipo de objeto para las tareas. Esta va a contener una tarea programada (frecuencia) de manera predeterminada.
 *
 *
 * @package App\Classes\DModule\Tasks
 * @see Schedule
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class Task
{

    private $schedule = null;
    private $method = "";

    /**
     * Task constructor.
     *
     * Will set up the Schedule once a day by default.
     *
     * Va a configurar la frecuencia para ejecutarse una vez al día de manera predeterminada.
     */
    public function __construct( string $method )
    {
        $this -> schedule   =   new Schedule();
        $this -> schedule   ->  setUpFrequency( 1 , 0 , 0 );
        $this -> method     =   $method;
    }

    /**
     * Gets the schedule object
     *
     * Obtiene el objeto de tarea programada
     *
     * @return Schedule|null
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Gets the method
     *
     * Obtiene el método
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }





}