<?php
/**
 * Created by PhpStorm.
 * User: danilo
 * Date: 30/1/2017
 * Time: 2:47 PM
 */

namespace App\Classes\DModule;


/**
 * Class DModuleResponsePlain
 * This is the Module Response of type plain
 *
 * Esta clase representa la respuesta de los módulos en formato plano
 *
 * @package App\Classes
 * @see DModuleResponse
 */
class DModuleResponsePlain extends DModuleResponse
{
    /**
     * This will contains the "content" of the response.
     *
     * Esta variable contiene el "contenido" de la respuesta.
     *
     * @var string
     */
    private $content;

    /**
     * Gets the content
     *
     * Obtiene el contenido
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sets the content
     *
     * Establece el contenido
     *
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }


}