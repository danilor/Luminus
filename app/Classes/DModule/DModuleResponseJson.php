<?php
/**
 * Created by PhpStorm.
 * User: danilo
 * Date: 30/1/2017
 * Time: 2:46 PM
 */

namespace App\Classes\DModule;


/**
 * Class DModuleResponseJson
 * This class is the Module response of type JSON
 *
 * Esta clase es la respuesta de los módulos de tipo JSON
 *
 * @package App\Classes
 * @link http://www.json.org/
 * @version 1.0
 * @author: Danilo Ramírez Mattey
 * @see DModuleResponse
 */
class DModuleResponseJson extends DModuleResponse
{
    /**
     * The data to be sent as a JSON
     *
     * La información a ser devuelta como JSON
     *
     * @var $data
     */
    private $data;


    /**
     * DModuleResponseJson constructor
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

     /**
     * This function executes the view and return the result as string.
      *
      * Esta función ejecuta la vista y devuelve el resultado como un string.
      *
     * @return string
     */
    public function execute(){
        return response()->json( $this -> data );
    }

    /**
     * Gets the data
     *
     * Obtiene la información
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data
     *
     * Establece la información
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}