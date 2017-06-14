<?php


namespace App\Classes\DModule;


/**
 * Class DModuleWidgetDefinition
 *
 * This class will work as a Module Widget Definition.
 *
 * Esta clase trabaja como la definición de los Widget de los módulos
 *
 * @package App\Classes\DModule
 * @see DModuleWidget
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class DModuleWidgetDefinition
{



    /**
     * This is the class of this definition
     *
     * Esta es la clase de esta definición
     *
     * @var string
     */
    private $class = "";
    /**
     * This is the method of the class we have to call
     *
     * Estes es el método de la clase que es necesario de llamar
     *
     * @var string
     */
    private $method = "";

    /**
     * The label for the widget
     *
     * la etiqueta del widget
     *
     * @var string
     */
    private $label = "";

    /**
     * The description of the widget
     *
     * La descripción del Widget
     *
     * @var string
     */
    private $description = "";

    /**
     * This is the exact date when this definition was created (Just in case)
     *
     * Esta es la fecha exacta cuando esta definición fue creada (solo por si acaso)
     *
     * @var \DateTime
     */
    private $created = null;

    /**
     * DModuleWidgetDefinition constructor.
     * @param string $class
     * @param string $method
     * @param string $label
     * @param string $description
     * @param string $label
     */
    public function __construct( string $class, string $method, string $label, string $description )
    {
        $this   ->class       = $class;
        $this   ->method      = $method;

        $this   ->description = $description;
        $this   ->label       = $label;
        $this   ->created     = new \DateTime();
    }

    /**
     * Gets the class name
     *
     * Obtiene el nombre de la clase
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Sets the class name
     *
     * Establece el nombre de la clase
     *
     * @param string $class
     */
    public function setClass(string $class)
    {
        $this->class = $class;
    }

    /**
     * Gets the method name
     *
     * Obtiene el nombre del método
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Sets the method name
     *
     * Establece el nombre del método
     *
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * Gets the label
     *
     * Obtiene la etiqueta
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Sets the label
     *
     * Establece la etiqueta
     *
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * Gets the description
     *
     * Obtiene la descripción
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * Establece la descripción
     *
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }




}