<?php


namespace App\Classes\DModule;


/**
 * Class DModuleNavigation
 *
 * This class will store the information for the Upper Navigation Menu
 * This is design and template dependent.
 *
 * Esta clase almacena la información para la navegación superior del sitio.
 * Este es dependiende del diseño del sitio.
 *
 * @package App\Classes\DModule
 */
class DModuleNavigation extends DModuleMenuGeneric
{
    /**
     * The attributes are only assigned to the Top Navigation
     *
     * Estos atributos son asignados solamente a la navegación superior.
     *
     * @var array
     */
    private $attributes = [];

    /**
     * Gets the attributes
     *
     * Obtiene los atributos
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Sets the attributes
     *
     * Establece los atributos
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Sets a single attribute
     *
     * Establece un solo atributo
     *
     * @param string $key
     * @param string $value
     */
    public function addAttribute(string $key , string $value ){
            $this -> attributes[] = [
                "key"           =>          $key,
                "value"         =>          $value,
            ];
    }


}