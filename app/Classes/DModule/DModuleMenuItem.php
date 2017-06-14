<?php

namespace App\Classes\DModule;

/**
 * Class DModuleMenuItem
 *
 * Each item of the menu
 *
 * Cada uno de los items del menú
 *
 * @package App\Classes\DModule
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class DModuleMenuItem
{
    private $segment_url = '';
    private $label = '';
    private $attributes = [];

    /**
     * Indicates if an administrator user is required to see this menu item
     *
     * Indica si es necesario un usuario administrador para ver este item del menu
     *
     * @var bool
     */
    private $UserAdministrator = false;


    /**
     * If set up, it will validate for all jobs indicated before showing the menu
     *
     * Si no está vacío, se validará por todos los trabajos indicados antes de mostrar el menú
     *
     * @var array
     */
    private $AllJobs = [];
    /**
     * If set up, it will validate for any jobs indicated before showing the menu
     *
     * Si no está vacío, se validará por cualquiera de los trabajos indicados antes de mostrar el menú
     *
     * @var array
     */
    private $AnyJobs = [];




    /**
     * Returns if the menu requires an administrator user
     *
     * Regresa si el menú requiere un usuario administrador
     *
     * @return bool
     */
    public function isUserAdministrator(): bool
    {
        return $this->UserAdministrator;
    }

    /**
     * Sets the user administration requirement
     *
     * Establece el requerimiento del usuario administrador
     *
     * @param bool $UserAdministrator
     */
    public function setUserAdministrator(bool $UserAdministrator)
    {
        $this->UserAdministrator = $UserAdministrator;
    }


    /**
     * Indicates the icon we want to use for the navigation menu.
     * Per default, it is using the font awesome icons
     *
     * Indica el ícono que queremos usar para el menú de navegación.
     * De manera predeterminada, utiliza los íconos de "FontAwesome".
     *
     * More information in: http://fontawesome.io/icons/
     * @var string
     * @link http://fontawesome.io/icons/
     */
    private $icon = '<i class="fa fa-gear"></i>';

    /**
     * if this menu has children, then they are going to be here.
     *
     * Si este menú tiene menús hijos, éstos van a encontrarse acá
     *
     * @var DModuleMenuItem[]
     */
    private $items = [];

    /**
     * DModuleMenuItem constructor.
     * Create the MenuItem
     *
     * DModuleItem Constructor.
     * Crea el MenúItem
     *
     * @param string $segment_url
     * @param string $label
     */
    public function __construct($segment_url = "", $label = "")
    {
        $this->segment_url = $segment_url;
        $this->label = $label;
    }


    /**
     * This method adds an attribute. If it already exists then it will be overwrite.
     *
     * Este método añade un atributo. Si este atributo ya existe, será sobreescrito.
     *
     * @param string $key
     * @param string $value
     */
    public function addAttribute(string $key , string $value ){
        $this -> attributes[ $key ] = $value;
    }

    /**
     * Gets the attributes list
     *
     * Obtiene la lista de atributos
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
     * Adds a single item to the menu (DModuleMenuItem)
     *
     * Añade un ítem único al menú (DModuleMenuItem)
     *
     * @param DModuleMenuItem $menuItem
     * @see DModuleMenuItem
     */
    public function addItemObject(DModuleMenuItem $menuItem ){
        $this -> items[] = $menuItem;
    }
    /**
     * Adds a single item indicting its segment and the label
     *
     * Añade un ítem único indicando su segmento y su etiqueta
     *
     * @param string $segment
     * @param string $label
     */
    public function addItem(string $segment , string $label ){
        $this -> items[] = new DModuleMenuItem( $segment , $label );
    }

    /**
     * Gets the segment URL
     *
     * Obtiene la URL del segmento
     *
     * @return string
     */
    public function getSegmentUrl(): string
    {
        return $this->segment_url;
    }

    /**
     * Sets the segment URL
     *
     * Establece la URL del segmento
     *
     * @param string $segment_url
     */
    public function setSegmentUrl(string $segment_url)
    {
        $this->segment_url = $segment_url;
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
     * Gets the items
     *
     * Obtiene la lista de items
     *
     * @return DModuleMenuItem[]
     * @see DModuleMenuItem
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Sets the items
     *
     * Establece los items
     *
     * @param DModuleMenuItem[] $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * This function indicates if this menu has sub menus
     *
     * Esta función indica si el menú tiene sub menus
     *
     * @return bool
     */
    public function hasChilds() : bool
    {
        return (bool)count( $this->items );
    }

    /**
     * Gets the current icon
     *
     * Obtiene el ícono actual
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Sets the current icon
     *
     * Establece el ícono actual
     *
     * @param string $icon
     */
    public function setIcon(string $icon)
    {
        $this->icon = $icon;
    }

    /**
     * Returns the list of all jobs
     *
     * Regresa la lista de todos los trabajos
     *
     * @return array
     * @see DModuleMenuItem::$AllJobs
     */
    public function getAllJobs(): array
    {
        return $this->AllJobs;
    }

    /**
     * Returns the list of any jobs
     *
     * Regresa la lista de cualquier trabajo
     *
     * @return array
     * @see DModuleMenuItem::$AnyJobs
     */
    public function getAnyJobs(): array
    {
        return $this->AnyJobs;
    }

    /**
     * Add a single job to the list of all Jobs
     *
     * Añade un trabajo individual a la lista de todos los trabajos
     *
     * @param string $j
     * @see DModuleMenuItem::$AllJobs
     */
    public function addJob_AllJobs(string $j){
        $this -> AllJobs[] = $j;
    }

    /**
     * Add a single job to the list of any Jobs
     *
     * Añade un trabajo individual a la lista de cualquiera de los trabajos
     * @param string $j
     * @see DModuleMenuItem::$AnyJobs
     */
    public function addJob_AnyJobs(string $j){
        $this -> AnyJobs[] = $j;
    }

    /**
     * Cleans the list of all jobs and any jobs
     *
     * Limpia la lista de Todos los trabajos y de Cualquier de los trabajos
     * @see DModuleMenuItem::$AnyJobs
     * @see DModuleMenuItem::$AllJobs
     */
    public function cleanJobs(){
        $this -> AllJobs = [];
        $this -> AnyJobs = [];
    }


    /**
     * Returns true if the list of any jobs has at least one job
     *
     * Regres verdadero si la lista de cualquiera de los tabajos tiene al menos un trabajo
     *
     * @return bool
     */
    public function hasAnyJobs() : bool
    {
        return (bool) count( $this -> AnyJobs );
    }

    /**
     * Returns true if the list of all jobs has at least one job
     *
     * Regres verdadero si la lista de todos los tabajos tiene al menos un trabajo
     * @return bool
     */
    public function hasAllJobs() : bool
    {
        return (bool) count( $this -> AllJobs );
    }
}