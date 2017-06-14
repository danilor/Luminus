<?php

namespace App\Classes\DModule;


/**
 * Class DModuleMenuGeneric
 *
 * This is the Generic Menu
 *
 * Este es el menú genérico
 *
 * @package App\Classes\DModule
 * @version 1.2
 * @author Danilo Ramírez Mattey
 */
class DModuleMenuGeneric
{
 /**
     * This should be the same name as the module. But in case we want to change it, that
     * is why there is a change to overwrite the DModuleMenu
  *
  *     Esto debería de ser el mismo nombre del módulo, pero en caso de que se desee cambiar existe la función para sobreescribir el título en DModuleMenu
     * @var string
  * @see DModuleMenuGeneric::setTitle()
     */
    protected $title;

    /**
     * Indicates the icon we want to use for the navigation menu.
     * Per default, it is using the font awesome icons
     *
     * Indica el ícono que queremos usar para este menú.
     * Predeterminado, este usa los íconos de las fuentes de "FontAwesome".
     *
     * More information in: http://fontawesome.io/icons/
     * @var string
     * @link http://fontawesome.io/icons/
     */
    protected $icon = '<i class="fa fa-gears"></i>';

    /**
     * The items of the Menu. Format: DModuleMenuItem
     *
     * Los itemes del Menu. Formato: DModuleMenuItem
     *
     * @var DModuleMenuItem[]
     * @see DModuleMenuItem
     */
    protected $items = [];



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
     * Gets the title
     *
     * Obtiene el título
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

    /**
     * Gets the menu items
     *
     * Obtiene los items del menú
     *
     * @return DModuleMenuItem[]
     * @see DModuleMenuItem
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Sets the menu items
     *
     * Establece los itemes del menú
     *
     * @param DModuleMenuItem[] $items
     * @see DModuleMenuItem
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }


    /**
     * Adds a single item to the menu (DModuleMenuItem) (Multiple calls)
     *
     * Añade un item único al menú (DModuleMenuItem) (Múltiples llamadas)
     *
     * @param DModuleMenuItem $menuItem
     * @see DModuleMenuItem
     */
    public function addItemObject(DModuleMenuItem $menuItem ){
        $this -> items[] = $menuItem;
    }


    /**
     * Adds a single item indicting its segment and the label. It is created internally, instead of sending it created.
     *
     * Añade un ítem único indicando su segmento y la etiqueta. Es creado internamente, en vez de mandarlo creado.
     *
     * @param string $segment
     * @param string $label
     * @internal  $item
     * @see DModuleMenuItem
     */
    public function addItem(string $segment , string $label ){
        $this -> items[] = new DModuleMenuItem( $segment , $label );
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