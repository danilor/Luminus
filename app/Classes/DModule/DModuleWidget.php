<?php
/**
 * Created by PhpStorm.
 * User: danilo
 * Date: 14/2/2017
 * Time: 9:51 AM
 */

namespace App\Classes\DModule;


/**
 * Class DModuleWidget
 *
 * This class will represent a widget element in the site.
 *
 * Esta clase representa un elemento widget dentro del sitio
 *
 * @package App\Classes\DModule
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class DModuleWidget
{

    /**
     * This is the content of the widget
     *
     * Este es el contenido del widget
     *
     * @var string
     */
    private $content = "";

    /**
     * The title of the widget (Not showing all the time)
     *
     * Este es el título del widget (No se muestra todo el tiempo)
     *
     * @var string
     */
    private $title = "";

    /**
     * This is the DModule where this comes from
     *
     * Este es el módulo de donde el widget proviene
     *
     * @var DModule
     */
    private $module = null;

    /**
     * DModuleWidget constructor.
     * @param DModule $module
     */
    public function __construct(DModule $module = null)
    {
        $this->module = $module;
    }


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

    /**
     * Gets the title
     *
     * Obtiene el tiulo
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * Establece el título
     *
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

}