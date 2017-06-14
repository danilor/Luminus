<?php

namespace Modules;

// Necesitamos extender el DModule
use App\Classes\DModule\DModule;
use App\Classes\DModule\DModuleMenuItem;
use App\Classes\DModule\DModuleNavigation;
use App\Classes\DModule\DModuleResponseJson;
use App\Classes\DModule\DModuleResponsePlain;
use App\Classes\DModule\DModuleResponseViewUnframed;
use App\Classes\DModule\DModuleTable;
use App\Classes\DModule\DModuleRequest;
use App\Classes\DModule\DModuleResponseView;
use App\Classes\DModule\DModuleWidget;

class tasks extends DModule
{

    private $new_prefix = "tk_";

    public function setUpDatabaseTables()
    {
        #region Tabla de Tareas
        $table = new DModuleTable();
        /**
         * Quiero sobreescribir el prefijo de estas tablas
         */
        $this->overwritePrefix( $this->new_prefix );
        $table -> setName("tasks");
        $table -> addBigIncrements( "id" , true );
        $table -> addBigInteger( "user_id" , true );
        $table -> addString( 'description' ,true , 256 );
        $table -> addDateTime( "created_at" , true );
        $table -> addDateTime( "updated_at" , true );
        $table -> addInteger("order",false);
        $table -> addBoolean("status",false);
        // El último parámetro está configurado para FALSO debido a que sabemos de antemano que la tabla de usuarios no le pertenece a este módulo
        $table -> addForeign( "user_id" , "tasks_users" , "users" , "id" , false );
        $this -> addTable( $table );
        #endregion
    }

    /**
     * This method will be executed everytime the module is loaded for every page. It must be implemented on the module class.
     *
     */
    public function start()
    {
        // Este módulo requiere a un usuario logueado
        $this -> setRequireUser( true );
        $this->overwritePrefix( $this->new_prefix );
        $this -> setGlobalCSS(  ["css/global_tasks.css"]  );
            $this -> setGlobalJS(   ["js/global_tasks.js"]   );
    }

    public function setUpTasks()
    {
        // TODO: Implement setUpTasks() method.
    }


    /**
     * This implementation should work as a configuration for the menu items.
     * of all tables that this module is going to use
     */
    public function setUpMenuItems()
    {
        //Estoy utilizando el DModuleNavigation debido a que no quiero que aparezca en el menu normal, sino en el superior solamente
        $Menu = new DModuleNavigation();
        /**
         * Estamos asignando un attributo de ID al menu de arriba
         * porque lo vamos a utilizar en algún momento.
         * La idea es mostrar la cantidad de tareas pendientes con un número, y eso solo se haría con Javascript
         */
        $Menu -> addAttribute( "id" , "tasks_top_menu_item" );
        $Menu -> setIcon( '<i class="fa fa-tasks"></i>' );
        //The see gallery option
        $MenuItem = new DModuleMenuItem( "add" , "Añadir Tarea" );
        $MenuItem -> addAttribute( "class" , "addTaskGlobalModule" );
        $MenuItem -> setIcon( '<i class="fa fa-plus"></i>' );
        $Menu -> addItemObject( $MenuItem );
        $MenuItem = new DModuleMenuItem( "show" , "Ver Tareas" );
        $MenuItem -> setIcon( '<i class="fa fa-list-ol"></i>' );
        $Menu -> addItemObject( $MenuItem );
        $MenuItem = new DModuleMenuItem( "history" , "Historial de Tareas" );
        $MenuItem -> setIcon( '<i class="fa fa-list-ol"></i>' );
        $Menu -> addItemObject( $MenuItem );
        $this -> setNavigation( $Menu );
    }

    /**
     * Shows the main gallery page
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_show( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;
        return $this->responseTypeView( "show" , $data , [] , ["js/tasks.js"] );
    }
    /**
     * Shows the main gallery page
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_history( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;
        return $this->responseTypeView( "show_history" , $data , [] , [] );
    }

    public function get_add( DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
        $data = [];
        $data[ "Module" ] = $this;
        return $this -> responseTypeViewUnframed("add",$data);
    }

    /**
     * Este método va a validar el texto, y si es válido, lo almacena.
     * @param DModuleRequest $Request
     * @return DModuleResponseJson
     * @internal $TTask Variable de tipo Model / Extended Model que me permite usar Eloquent dentro de mi módulo.
     * @see DModuleResponseJson
     */
    public function post_save( DModuleRequest $Request ) : DModuleResponseJson
    {
        $rules = array(
			'text' 			    => 'required|min:5',
		);
		$validator = \Validator::make( $Request->getInput() , $rules );
		if ($validator -> fails()) {
		    return $this -> responseTypeJson(["result" => 0]);
		}else{
            /**
             * Podemos salvar la información.
             * @var TaskItem Este objeto TTask debería ser una extensión del eloquent de Laravel
             */
		    $TTask = new TaskItem();
		    $TTask -> user_id       =   $Request -> getUser() -> id;
		    $TTask -> description   =   $Request -> input("text");
		    $TTask -> order         =   99999;
		    $TTask -> status        =   1;
		    $TTask -> save();

		    return $this -> responseTypeJson(["result" => 1]);
		}
    }

    /**
     * Este método se debería encargar de las acciones de las tareas.
     * @param DModuleRequest $Request
     * @return DModuleResponseJson
     */
    public function get_action( DModuleRequest $Request ) : DModuleResponseJson
    {
        if( $Request->input("done") != "" && is_numeric( $Request->input("done") ) ){
            $TTask = TaskItem::where("id" , (int) $Request->input("done") ) -> where("user_id" , $Request->getUser()->id ) -> first();
            if( $TTask != null ){
                $TTask -> status = 0;
                $TTask -> save();
                return $this -> responseTypeJson(["result" => 1]);
            }else{
                return $this -> responseTypeJson(["result" => 0]);
            }
        }elseif( $Request->input("delete") != "" && is_numeric( $Request->input("delete") ) ){
            $TTask = TaskItem::where("id" , (int) $Request->input("delete") ) -> where("user_id" , $Request->getUser()->id ) -> first();
            if( $TTask != null ){
                $TTask -> status = 0;
                $TTask -> delete();
                return $this -> responseTypeJson(["result" => 1]);
            }else{
                return $this -> responseTypeJson(["result" => 0]);
            }
        }elseif( $Request->input("order_ids") != "" ){
            $order_ids = $Request->input("order_ids");
            $cont = 0;
            foreach( explode( ",",$order_ids ) AS $id ){
                $TTask = TaskItem::where( "id" , $id )->where( "user_id" , $Request->getUser()->id )->first();
                if( $TTask != null ){
                    $TTask -> order = $cont++;
                    $TTask -> save();
                }
            }
        }elseif(  $Request->input("number") === "all" ){
            $total = TaskItem::where( "user_id" , $Request->getUser()->id )->where("status",1)->count();
            return $this -> responseTypeJson([ "result" => 1 , "total" => $total]); // 0 = no hizo nada
        }
        return $this -> responseTypeJson(["result" => 0]); // 0 = no hizo nada
    }

    /**
     * This method gets the list of TO do tasks with the full HTML (so just grab and print).
     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     * @see DModuleResponseUnframed
     */
    public function get_list( DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
        $data = [];
        $data[ "Module" ] = $this;
        return $this -> responseTypeViewUnframed("tasks_list",$data);
    }

    /**
     * This method will work in the site dashboard for a single user.
     * It is not like the rest of the methods, and won't read any parameter.
     * The response must be a DModuleWidget object, if it is not, then nothing will show up.
     * @return DModuleWidget
     * @see DModuleWidget
     */
    public function widget_showtasks( ) : DModuleWidget
    {
        $Widget = new DModuleWidget();
        $data = [];
        $data[ "Module" ] = $this;
        /**
         * Lets read the data and send it to the view.
        */
        $view = $this -> getSimpleViewObject( "dashboard" , $data );
        /**
         * Now that we have the view, we can send the "plain" html of the render.
         * That "plain html" is the one that is going to be show in the dashboard.
         */
        $Widget -> setContent( $view->render() );
        $Widget -> setTitle( 'Últimas Tareas' );
        return $Widget;
    }

    /**
     * This method shows the widget with the number of total tasks.
     *
     * Este método muestra el widget con el número de tareas total
     *
     * @return DModuleWidget
     * @see DModuleWidget
     */
    public function widget_totaltasks() : DModuleWidget
    {
        $Widget = new DModuleWidget();

        $data = [];
        $data[ "Module" ] = $this;
        /**
         * Lets read the data and send it to the view.
        */
        $view = $this -> getSimpleViewObject( "total_widget" , $data );

        /**
         * Now that we have the view, we can send the "plain" html of the render.
         * That "plain html" is the one that is going to be show in the dashboard.
         */

        $Widget -> setContent( $view->render() );


        return $Widget;
    }



}