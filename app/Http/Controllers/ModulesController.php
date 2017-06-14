<?php
namespace App\Http\Controllers;
use App\Classes\Common;
use App\Classes\DModule;
use App\Classes\DModule\DModuleAssets;
use App\Classes\DModule\DModuleRequest;
use App\Classes\DModule\DModuleResponse;
use App\Classes\DModule\DModuleResponseView;

use App\Classes\DModule\File\FileAttempt;
use App\Classes\DModule\File\FileManager;
use App\Classes\System\SystemError;
use App\Classes\System\SystemException;
use App\Classes\System\SystemNotFoundException;

use App\Classes\Util\Zippde;
use App\UserDashboard;
use Auth;
use Dompdf\Exception;
use Request;
use Validator;
use Input;
use Redirect;
use File;

/**
 * Class ModulesController
 * This class will manage everything related to the dynamic modules structure
 * Esta clase va a controlar todo lo relacionado con la estructura de los módulos dinámicos
 * @package App\Http\Controllers
 */
class ModulesController
{

    /**
     * The main modules URL
     * La URL principal de los módulos
     * @var string
     */
    private $main_url = '/modules';

    /**
     * The main dynamic modules URL
     * La principal URL de los módulos dinámicos
     * @var string
     */
    public static $module_url = '/m';

    /**
     * This will show the module page.
     * We grab the name from the url and the area (segment 3) we want to show.
     * Obtenemos el nombre de la URL y el área (segmento 3) que queremos mostrar
     *
     * >_< Almost everything in the site should pass through here
     * >_< Casi todo en el sitio debería de pasar por aquí
     *
     *
     * （；¬＿¬) ==> For some reason the PHPStorm wont recognize the "System" namespace, even when I am 100% is there and its working  <== ヾ( ･`⌓´･)ﾉﾞ
     *
     * @internal $ModuleClass The module class | La Clase del módulo
     * @see config/modules.php
     * @see DModule\DModule
     * @see Common
     * @see DModule\DModuleRequest
     * @see SystemException
     * @see SystemError
     * @see App\Classes\System\SystemNotFoundException
     *
     * */
    public function showModule(){

        /**
         * First we lowercase the url parameters because we want full browser compatibility
         * Primero convertimos a minúscilas todos los parámetros del url porque queremos tener completa compatibilidad con los navegadores
         */
        $module_name    =   strtolower( Request::segment( 2 ) );
        $method_name    =   strtolower( Request::segment( 3 ) );
        $method_type    =   strtolower( Request::getMethod() );

        /**
         * If the method name is empty, we are going to replace it with a default method name. So if the class is called by its own, then we are going to show the base method.
         * Si el método está vacío, vamos a remplazarlo con el nombre de método predeterminado. Así, si la clase es llamada por sí misma solamente, entonces vamos a mostrar el método base.
         */

        if( $method_name == "" ) $method_name = config( "modules.default_method_name" ); // it's empty | está vacío

        /**
         * First, we need to verify that the module is actually installed.
         * Primero verificamos que el módulo esté instalado realmente
         */

        try {
            /**
             * $ModuleClass
             * This is the main module class
             * Esta es la clase principal del módulo
             */
            $ModuleClass = \App\Module::loadModuleClass($module_name);

            /**
             * Now we set up some variables inside the $ModuleClass. Since all modules should extends from DModule, then we are sure that the following methods exist
             * Ahora tenemos que configurar algunas variables dentro del $ModuleClass. Debido a que todos los módulos deberían de extender de DModule, entonecs estamos seguros de que los siguientes métodos existen
             */
            $ModuleClass->setBaseModuleUrl(ModulesController::$module_url . '/' . $ModuleClass->getClassName()); // The base URL | El URL base
            $ModuleClass->setAssetsBaseUrl($ModuleClass->getBaseModuleUrl() . '?asset='); // The base URL for the assets | El URL base para los assets
            $ModuleClass->setDocumentsBaseUrl($ModuleClass->getBaseModuleUrl() . '?document='); // The base URL for the documents | El URL base para los documentos

            /**
             * This is the default call
             * Esta es la llamada predeterminada
             */
            $ModuleClass->start(  );

            /**
             * Now we try to load the module definition. This is the JSON file next to the main Class
             * Ahora intentamos cargar la definición del módulo. Este es el archivo JSON junto a la clase principal
             */
            $ModuleDefinition = \App\Module::loadModuleDefinition($module_name);

            /**
             * At last, we load the Module Registry inside the database
             * Al final, cargamos el registro del módulo dentro de la base de datos
             */
            $ModuleRegistry = \App\Module::getModuleRegistry($module_name);
        }catch(\Error $err){
            /**
             * If there is any error in any of the process above
             * (load module class, load module definition or load module registry)
             * those methods are throwing errors, so that means that we
             * cannot load the Module and we throw the error
             *
             * Si existe cualquier error en alguno de los procesos de arriba
             * (cargar la clase del módulo, cargar la definición del módulo o cargar el registro del ´modulo)
             * estos métodos tiran errores, lo que significa que no se pudo cargar
             * el módulo y con ello mostramos el error.
             *
             * In both cases we send a 404 page
             * En ambos casos mandamos una página 404
             */
            return Common::send404("Could not load the module");
        }catch(\Exception $err){
            /**
             * If there is any exception in any of the process above
             * (load module class, load module definition or load module registry)
             * those methods are throwing exceptions, so that means that we
             * cannot load the Module and we throw the exception
             *
             * Si existe cualquier excepción en alguno de los procesos de arriba
             * (cargar la clase del módulo, cargar la definición del módulo o cargar el registro del ´modulo)
             * estos métodos tiran excepciones, lo que significa que no se pudo cargar
             * el módulo y con ello mostramos la excepción.
             *
             * In both cases we send a 404 page
             * En ambos casos mandamos una página 404
             */
            return Common::send404("Could not load the module");
        }

        /**
         * WE MOVED THE ASSET CHECK BECAUSE WE DON'T WANT THE ASSETS TO BE USER RESTRICTED (unframed and Auth exclusions)
         * Before checking for the method, We want to know
         * if there is a request for an asset so we can bring it.
         *
         * MOVIMOS LA REVISIÓN DE LOS ASSETS PORQUE NO QUEREMOS QUE LOS ASSETS ESTÉN ATADOS A LAS RESTRICCIONES DE USUARIO. (Unframed y Exclusiones de autorización)
         * Antes de llamar al método, queremos saber si existe alguna
         * petición para un recurso que tengamos que traer
         *
         * щ(ಠ益ಠщ) What an issue, I am not sure if in the future we will want assets that are auth restricted and others that are not.
         */

        if( Input::get("asset") != "" ){
            $asset_name = Input::get("asset");
            /**
             * Since the asset is not empty, then this means we have to execute an special process to get the assets.
             * Debido a que el recurso no está vacío, esto significa que tenemos que ejecutar un proceso especial para traer el recurso
             */
            return DModuleAssets::getAssetContent( $ModuleClass , $asset_name );
        }


        /**
         * Now we check if the module requires to be logged in
         * Ahora revisamos si el ´modulo requiere que se esté logueado
         */

        $user = null;

        /**
         * IF the module require an authorization access, AND the method name IS NOT in the AuthorizationExclusions of the class, then we show the login page.
         * SI el módulo requiere de un acceso autorizado, Y el nombre del método NO ESTÄ en la lista de exclusiones de autorización de la clase, entonces mostrarmos la página de login.
         */

        if( ($ModuleClass->needsUser() && !Auth::check()) && !in_array( $method_name , $ModuleClass->getAuthorizationExclusions() ) ){
            /**
             * The login page
             * La página de login
             */
            return Common::sendLogin();
        }else{
            $user = \Auth::user();

            if( !is_null( $user ) ){
                /**
                 * Now that the user is loaded, we are going to preload all jobs in case the module needs them
                 * Ahora que el usuario está cargado, vamos a precargar todos los trabajos en caso de que el módulo los requiera
                 */
                $user -> loadJobs();
            }

        }


        /**
         * The ModuleRequest will hold all information a module may require, just as input, url, session and current user.
         * El ModuleRequest va a contener toda la información que el módulo pueda requerir, como el input, la URL, la sesión y el usuario activo.
         */
        $ModuleRequest  = new DModuleRequest( $user );

        /**
         * if there is any module requirement, we are adding them to the request.
         * Si existe algún requerimiento de módulo, lo añadimos al ModuleRequest
         *
         * We are reading the module requirements from the original module definition.
         * Estamos leyendo los requerimientos del módulo de la definición del módulo original.
         * */
        if ( isset($ModuleDefinition->module_requirements) ) {
            foreach( $ModuleDefinition->module_requirements AS $ModuleRequirement ){
                    try{
                        /**
                         * Just as we did with the main module loading, we have to verify for the module class, the module definition and module registry to add it to the current request
                         * Justo como hicimos con la carga del módulo principal, tenemosque verificar para cada extra módulo su definición y su registro para añadirlo al request actual
                         */

                        /**
                         * Module class
                         * Clase de módulo
                         */
                        $ExtraModule = \App\Module::loadModuleClass($ModuleRequirement->name);

                        /**
                         * We set up the variables of the URL addresses
                         * Configuramos las variables con las direcciones  URL
                         */
                        $ExtraModule    ->      setBaseModuleUrl(   ModulesController::$module_url . '/' . $ExtraModule->getClassName()     );
                        $ExtraModule    ->      setAssetsBaseUrl(   $ExtraModule->getBaseModuleUrl() . '?asset='                            );
                        $ExtraModule    ->      setDocumentsBaseUrl(    $ExtraModule->getBaseModuleUrl() . '?document='                     );

                        /**
                         * We execute the module start
                         * Ejecutamos el inicio del módulo
                         */
                        $ExtraModule -> start();

                        /**
                         * Now that the module is loaded, we added it to the Request
                         * Ahora que el módulo está cargado, lo añadimos al Request
                         */

                        $ModuleRequest->addExtraModule( $ExtraModule );
                    }catch ( \Error $err ){
                        /**
                         * Different from the main module, if there is any error loading the extra module we don't show a 404 page but send a SystemError
                         * Diferente al módulo principal, si existe cualquier error cargando el módulo extra, no mostramos la página de 404, sino que enviamos un SystemError
                         */
                        throw new SystemError('Error loading the module requirement: ' . @$ModuleRequirement -> name);
                    }catch( \Exception $err ){
                        /**
                         * Different from the main module, if there is any exception loading the extra module we don't show a 404 page but send a SystemException
                         * Diferente al módulo principal, si existe cualquier excepción cargando el módulo extra, no mostramos la página de 404, sino que enviamos un SystemException
                         */
                        throw new SystemException('Exception loading the module requirement: ' . @$ModuleRequirement -> name);
                    }
            }
        }

        /**
         * If there is any module optional, we are adding them to the Request. Differennt from the extra module validation, if these modules cannot be load, we don't throw errors or exceptions
         * Si existe cualquier módulo opcional, los añadimos al Request. Diferente a la validación de los módulos extras, si estos módulos no pueden ser cargados, no tiramos errores o excepciones
         * */
        if ( isset($ModuleDefinition->module_optional) ) {
            foreach( $ModuleDefinition->module_optional AS $ModuleRequirement ){
                    try{
                        /**
                         * Just as we did with the main module loading, we have to verify for the module class, the module definition and module registry to add it to the current request
                         * Justo como hicimos con la carga del módulo principal, tenemosque verificar para cada extra módulo su definición y su registro para añadirlo al request actual
                         */

                        /**
                         * Module class
                         * Clase de módulo
                         */
                        $ExtraModule = \App\Module::loadModuleClass($ModuleRequirement->name);
                        /**
                         * We set up the variables of the URL addresses
                         * Configuramos las variables con las direcciones  URL
                         */
                        $ExtraModule->setBaseModuleUrl(ModulesController::$module_url . '/' . $ExtraModule->getClassName());
                        $ExtraModule->setAssetsBaseUrl($ExtraModule->getBaseModuleUrl() . '?asset=');
                        $ExtraModule->setDocumentsBaseUrl($ExtraModule->getBaseModuleUrl() . '?document=');

                        /**
                         * We execute the module start
                         * Ejecutamos el inicio del módulo
                         */
                        $ExtraModule -> start();

                        /**
                         * Now that the module is loaded, we added it to the Request
                         * Ahora que el módulo está cargado, lo añadimos al Request
                         */
                        $ModuleRequest->addExtraModule( $ExtraModule );
                    }catch ( \Error $err ){
                        /**
                         * NOTHING TO DO
                         * NADA QUE HACER
                         */

                    }catch( \Exception $err ){
                        /**
                         * NOTHING TO DO
                         * NADA QUE HACER
                         */
                    }

            }
        }



         /**
         * Before checking for the method, We want to know
         * if there is a request for documents so we can bring them.
         *
          * Antes de llamar al método, queremos saber si existe alguna petición
          * para un documento que tengamos que traer
         *
         */

         if( Input::get("document") != "" ){
                $document_name = Input::get("document");
                /**
                 * Since the asset is not empty, then this means we have to execute an special process to get the assets.
                 * Debido a que el recurso no está vacío, esto significa que tenemos que ejecutar un proceso especial para traer el recurso
                 */
                 return DModuleAssets::getDocumentContent( $ModuleClass , $document_name );
         }

        /**
         * Now we have to build the method name to see if it exists.
         * It has the following structure:
         *
         * Ahora tenemos que construir el nombre del método para saber si existe.
         * Posee la siguiente estructura:
         *
         * [METHOD_TYPE]_[NAME]
         * [TIPO DE MÉTODO]_[NOMBRE]
         *
         * METHOD TYPE refers to the type or request (GET, POST, PUT or DELETE)
         * El TIPO DE MÉTODO se refiere al tipo de petición (GET, POST, PUT o DELETE)
         */

         $class_method = $method_type . '_' . $method_name ;

        /**
         * Check if the method exists
         * Revisamos si el método existe
         */

        if( method_exists( $ModuleClass , $class_method ) ){

            try{
                $ModuleResponse = $ModuleClass -> $class_method( $ModuleRequest );
            }catch (SystemNotFoundException $err){
                /**
                 * The method can throw a SystemNotFoundException, so that means that we have to show the 404 page. This is controller by the module itself
                 * El método puede arrojar un SystemNotFoundException, lo que significa que tenemos que mostrar la página 404. Esto es controlado por el módulo propiamente.
                 */
                 return Common::send404( $err->getMessage() );
            }

            /**
             * Now, depending of the response we can show different elements
             * Ahora, dependiendo del tipo de respuesta podemos mostrar distintos elementos
             */

            return $this -> sendResponseBack( $ModuleClass , $ModuleResponse , $ModuleRegistry );


        }else{
            /**
             * If the method does not exist, then we send the 404 page
             * Si el método no existe, enviamos la página 404
             */
            return Common::send404( "Class method does not exist" );
        }
    }

    /**
     * This method takes a DModuleResponse object and send a response depending on the type of the response
     * Este método toma un objeto DModuleResponse y envía una respuesta dependiendo del tipo de respuesta que se requiere
     *
     * @param DModule\DModule $ModuleClass
     * @param DModuleResponse $ModuleResponse
     * @param \App\Module $ModuleRegistry
     * @return $this|string
     */
    private function sendResponseBack(DModule\DModule $ModuleClass , DModuleResponse $ModuleResponse , \App\Module $ModuleRegistry){
            if( $ModuleResponse instanceof DModuleResponseView ){
                /**
                 * If we have a response of the type VIEW then we have to load the "shell" of the site and render the view inside that "shell"
                 * Si tenemos una respuesta de tipo VIEW, entonces tenemos que cargar la "cáscara" del sitio y renderizar la vista dentro de esta "cáscara".
                 */

                $data = [];
                $data["ModuleClass"] = $ModuleClass;
                $data["ModuleRegistry"] = $ModuleRegistry;
                $data["ModuleResponse"] = $ModuleResponse;
                /**
                 * We are commenting the following line because we don't want to store any more logs on opening modules.
                 * Estamos comentando la linea siguiente porque no queremos que almacene más logs al abrir módulos.
                 */
               // if( Auth::check() ) Auth::user() -> logAction( "Load Module" , $ModuleClass->getClassName() );

                /**
                 *  We send all necesary information to the view, like the Request, the ModuleClass and the response
                 *  Enviamos toda la información necesaria a la vista, como el Request, la clase del módulo y la respuesta
                 */
                return view("main.module.module_view")->with($data);
             }elseif( $ModuleResponse instanceof DModule\DModuleResponseViewUnframed ){

                /**
                 * If we have the response of the type UNFRAMED then we have to show the response structure just as it comes
                 * Si tenemos una respuesta de tipo UNFRAMED entonces tenemos que mostrar la respuesta tal y como viene
                 */

                $data = [];
                $data["ModuleClass"] = $ModuleClass;
                $data["ModuleRegistry"] = $ModuleRegistry;
                $data["ModuleResponse"] = $ModuleResponse;
                /**
                 * We are commenting the following line because we don't want to store any more logs on opening modules
                  Estamos comentando la linea siguiente porque no queremos que almacene más logs al abrir módulos.
                 */
                // if( Auth::check() ) Auth::user() -> logAction( "Load Module" , $ModuleClass->getClassName() );
                /**
                 * In thise case we are not sending anything to any view, but executing the ModuleResponse directly
                 * En este caso no estamos mandando nada a la vista, pero ejecutando la respuesta del módulo directamente
                 */
                return $ModuleResponse->executeView();
            }elseif( $ModuleResponse instanceof DModule\DModuleResponseJson ){

                /**
                 * if we have to show the data as a JSON response, then we just execute the ModuleResponse object (That automatically should convert all data into JSON)
                 * Si tenemos que mostrar la información como una respuesta JSON, entonces ejecutamos la respuesta del ModuleResponse (Que automáticamente debería de convertir toda la información en JSON)
                 */

                /**
                 * We are commenting the following line because we don't want to store any more logs on opening modules
                 * Estamos comentando la linea siguiente porque no queremos que almacene más logs al abrir módulos.
                 */
                // if( Auth::check() ) Auth::user() -> logAction( "Load Module" , $ModuleClass->getClassName() );
                return $ModuleResponse->execute();
            }elseif( $ModuleResponse instanceof DModule\DModuleResponseRedirect ){

                /**
                 * If we have a response of type Redirect, it is just as simple as redirect the page. The DResponseRedirect object should take care of all append information to the URL.
                 * Si tenemos una respuesta de tipo Redirect, es una redirección de la página simple. El objeto DResponseRecirect deberúia de hacerse cargo de añadir toda la información a la URL.
                 */

                /**
                 * We get the URL from the DModuleResponseRedirect Object. This should include the query string if there is any
                 * Obtenemos la URL del objeto de DModuleResponseRedirect. Esto debería de incluir el query string si existe alguno.
                 */

                $url = $ModuleResponse->getFullBuiltUrl();

                /**
                 * We prepare the Redirect
                 * Preparamos el Redireccionamiento
                 */
                $redirect = \Redirect::to( $url );

                /**
                 * If there is any errors to return, then we attach them to the redirect
                 * Si existe algún error que retornar, entonces los añadimos a la redirección
                 */
                if( count($ModuleResponse->getErrors()) > 0 ){
                    $redirect -> withErrors( $ModuleResponse->getErrors() );
                }

                if( count($ModuleResponse->getInput()) > 0 ){
                    $redirect -> withInput( $ModuleResponse->getInput() );
                }

                /**
                 * If there is any input to return, then we attach them to the redirect
                 * Si existe algún campo que retornar, entonces los añadimos a la redirección
                 */
                return $redirect;

            }elseif( $ModuleResponse instanceof DModule\DModuleResponsePlain ){

                /**
                 * If we have the response of the type Plain then we have to show the response text
                 */

                $data = [];
                $data["ModuleClass"] = $ModuleClass;
                $data["ModuleRegistry"] = $ModuleRegistry;
                $data["ModuleResponse"] = $ModuleResponse;
                /**
                 * We are commenting the following line because we don't want to store any more logs on opening modules
                Estamos comentando la linea siguiente porque no queremos que almacene más logs al abrir módulos.
                 */
                // if( Auth::check() ) Auth::user() -> logAction( "Load Module" , $ModuleClass->getClassName() );
                /**
                 * In thise case we are not sending anything to any view, but executing the ModuleResponse directly
                 * En este caso no estamos mandando nada a la vista, pero ejecutando la respuesta del módulo directamente
                 */
                return $ModuleResponse->getContent();
            }
    }

    /**
     * This method will show the list of installed modules
     * Este método va a mostrar la lista de módulos instalados
     * @return $this|Redirect
     */
    public function showInstalledModules()
    {
        $data = [];
        $data["installed"] = 1;
        $list = \App\Module::getModulesListInformation();
        foreach( $list AS $key => $l ){
            $action = "Actualizado";
            $list[ $key ] -> update = false;
            $list[ $key ] -> status = 0;
            if( $l->available_version > $l->version ){
                $action = "Actualizar";
                $list[ $key ] -> status = 1;
                $list[ $key ] -> update = true;
            }elseif($l->available_version < $l->version){
                $list[ $key ] -> status = -1;
                $action = "Error";
            }
            $list[ $key ] -> action = $action;
         }
        $data[ "Modules" ] = $list;
        return view("main.module.installed") -> with( $data );
    }

    /**
     * This function will show the not installed modules
     * Esto va a mostrar la lista de los módulos no instalados
     */
    public function showNotInstalledModules(){
        $data = [];
        $data["installed"] = 0;
        $installed_modules = [];
        $list = \App\Module::getModulesListInformation();
        foreach( $list AS $l ){
            $installed_modules[] = $l -> name;
        }
        $path = base_path( "modules" );
        $aux_list_of_modules = File::directories( $path );
        $list_of_not_installed_modules = [];

        /**
         * We are searching for the modules that are not installed
         * Estamos buscando por aquellos módulos que no están instalados
         */
        foreach( $aux_list_of_modules AS $l ){
            $aux = preg_split("/[\/,\\\]+/", $l);
            $aux_last = $aux[ count($aux) - 1 ];
            /**
             * Lets check if the module is installed or not
             */
            if( !in_array( $aux_last , $installed_modules ) ){
                $list_of_not_installed_modules[] = $aux_last;
            }
        }

        $modules_definitions = [];
        foreach( $list_of_not_installed_modules AS $module ){
            try{
                $modules_definitions[] = \App\Module::loadModuleDefinition( $module );
            }catch (\Exception $err){}catch (\Error $err){}
        }

        foreach( $modules_definitions AS $key => $ModuleDefinition ){
            $modules_definitions[ $key ] -> status = -1;
        }
        $data[ "Modules" ] = $modules_definitions;
        return view("main.module.installed") -> with( $data );
    }

    /**
     * This method will execute an action on one module (not controlled by a dynamic module) and return to an url or return a JSON response
     * Este método va a ejecutar una acción en un módulo (no controlado por un módulo dinámico) y devolver una url o regresar una respuesta JSON
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function actionModule(){

        /**
         * Uninstalling the module
         */
        if( \Input::get("uninstall") != "" ){
            $name = \Input::get("uninstall");
            try{
                \App\Module::uninstallModule( $name );
            }catch (\Exception $err){
                return redirect(  $this -> main_url . "/installed?error_uninstall=" . $name );
            }catch(\Error $err){
                return redirect( "/modules/installed?error_uninstall=" . $name );
            }
            return redirect(  $this -> main_url . "/installed" );
        }

        /**
         * Updating the module
         */
        if( \Input::get("update") != "" ){
            $name = \Input::get("update");
            try{
                \App\Module::updateModule( $name );
            }catch (\Exception $err){
                return redirect(  $this -> main_url . "/installed?error_update=" . $name );
            }catch(\Error $err){
                return redirect( $this -> main_url . "/installed?error_update=" . $name );
            }
            return redirect(  $this -> main_url . "/installed" );
        }

        /**
         * Install the module
         */
        if( \Input::get("install") != "" ){
            $name = \Input::get("install");
            try{
                \App\Module::installModule( $name );
            }catch (\Exception $err){
                return redirect( "/modules/not_installed?error_install=" . $name );
            }catch(\Error $err){
                return redirect(  $this -> main_url . "/not_installed?error_install=" . $name );
            }
            return redirect(  $this -> main_url . "/not_installed" );
        }

        /**
         * Reorder the modules
         */
        if( \Input::get("reorder") != "" ){ // AJAX
                $ids = explode( ',' , \Input::get("reorder") );
                if( count( $ids ) > 0 ){
                    $cont = 0;
                    foreach( $ids AS $id ){
                        $ModuleRegistry = \App\Module::find( $id );
                        if( $ModuleRegistry != null ){
                            $ModuleRegistry -> changeOrderNumber( $cont++ );
                        }
                    }
                }
                \App\Module::setUpModuleCacheMenu();
                return response()->json(['response' => '1']);
        }

        /**
         * Update the modules (but in the main dashboard page)
         */
        if( \Input::get("update_user_modules_order") != "" ){
            $items = explode( "|" , \Input::get("update_user_modules_order") );
            $cont = 0;
            foreach( $items AS $item ){
                $module = explode( "," , $item  );
                $aux = UserDashboard::find( $module[ 0 ] );
                if( $aux != null ){
                    $aux -> order = $cont++;
                    $aux -> side = $module[ 1 ];
                    $aux -> save();
                }
            }
            return response()->json(['response' => '1']);
        }

        /**
         * Pack the module
         */
        if( \Input::get("pack") != "" ){
            $name = \Input::get("pack");
            try{
                $filename = \App\Module::packModule( $name );
            }catch (\Exception $err){
                return redirect( "/modules/installed?error_pack=" . $name . '&d=' . $err->getMessage() );
            }catch(\Error $err){
                return redirect(  $this -> main_url . "/installed?error_pack=" . $name . '&d=' . $err->getMessage() );
            }
            return redirect(  $this -> main_url . "/installed?packed=" . $filename );
        }

        if( \Input::get("download_package") != "" ){
            $name = \Input::get("download_package");

            $path = storage_path( \App\Module::MODULE_PACKAGE_FOLDER ) . DIRECTORY_SEPARATOR . $name . \App\Classes\Util\Zippde::DEFAULT_EXTENSION;

            return response()->download( $path );
        }


        /**
         * Function to install a module from a zipfile
         */
        //
        if( \Input::get("install_zip") === "y" ){


            /**
             * We are using the same upload module for
             */
            $temporal_name = 'zip' . \Auth::user()->id;
            $temporal_path = storage_path( config("filesystems.temporal_folder") );
            $FileAttempt = new FileAttempt( "zip" , ['zip'] , 1400000 , "" );
            $FileAttempt -> overwriteAutoName( $temporal_name );
            $FileManager = new FileManager( "global" );
            $default_message = "El archivo ZIP no pudo ser subido debido a que no cumplía con los requisitos.";
            $error_url = '/modules/install_from_zip';
            $success_url = "/modules/not_installed";
            try{
                $File = $FileManager->attemptFileUploadCustom( $FileAttempt , $temporal_path );
            }catch (\Error $err){
                //dd( $err->getMessage() , $err->getTrace() );
                return \Redirect::to( $error_url ) -> withErrors( [$default_message] );
            }catch (\Exception $err){
                //dd( $err->getMessage() , $err->getTrace() );
                return \Redirect::to( $error_url ) -> withErrors( [$default_message] );
            }

            $zipde = new Zippde( $temporal_name , $temporal_path  );

            $default_message = "Ocurrió un error descomprimiendo el archivo ZIP en la aplicación. Verifique que la información y el archivo efectivamente sea un archivo .ZIP.";
            $extract_path = '';
            try{
                $extract_path = $zipde -> extract();
            }catch (Exception $err){
                return \Redirect::to( $error_url ) -> withErrors( [$default_message] );
            }
            \File::delete( $File->getRealLocation() . DIRECTORY_SEPARATOR . $File->getFullFilename() );

            /**
             * Now its time to check if the right folders are in the right places.
             */

            $default_message = "La estructure del archivo ZIP parece incorrecta";
            $folders_to_check = [$extract_path.DIRECTORY_SEPARATOR."modules",$extract_path.DIRECTORY_SEPARATOR."views/modules"];



            /**
             * Main Modules Folder
             */
            $folder = $extract_path.DIRECTORY_SEPARATOR."modules" ;
            if(\File::isDirectory( $folder )) {
                $success = \File::copyDirectory( $folder , base_path('modules') );
                if( $success == false ){
                    return \Redirect::to( $error_url ) -> withErrors( [ "El folder de módulo no pudo ser copiado" ] );
                }
            }else{
                // Since this is the main folder, it is required to be there
                return \Redirect::to( $error_url ) -> withErrors( [ "El folder de módulo no existe" ] );
            }

            \File::deleteDirectory( $folder );

            // This one does't matter if it exists or not
            $folder = $extract_path.DIRECTORY_SEPARATOR."views/modules" ;
            if(\File::isDirectory( $folder )) {
                $success = \File::copyDirectory( $folder , resource_path('views/modules') );
                if( $success == false ){
                    return \Redirect::to( $error_url ) -> withErrors( [ "El folder de módulo no pudo ser copiado" ] );
                }
            }
            \File::deleteDirectory( $folder );
            return \Redirect::to( $success_url );
        }


    }

    /**
     * This method will take care of all normal and usual actions for the modules.
     * Este método va a tomar control y se va a encargar de todas las acciones normales y usuales de los módulos.
     * @return \Illuminate\Http\JsonResponse
     */
    public function regularActionModule(){
        $response = [];
        if( \Input::get("enable_dashboard") != "" ){
            $data = \Input::get("enable_dashboard");
            $data = explode( "|" , $data );

            $aux = new UserDashboard();
            $aux -> user_id     =   \Auth::user()->id;
            $aux -> module      =   $data[ 0 ];
            $aux -> method      =   $data[ 1 ];
            $aux -> label       =   $data[ 2 ];
            $aux -> order       =   99;
            $aux -> side        =   'l'; //Per default we are adding it to the left side
            $aux -> save();
            $response[ "new_id"]    =   $aux -> id;
            $response[ "success"]   =   1;
        }
        if( \Input::get("disable_dashboard") != "" ){
            $data = \Input::get("disable_dashboard");
            $data = explode( "|" , $data );
            UserDashboard::where( "user_id" , \Auth::user()->id ) -> where( "module" , $data[ 0 ] ) -> where( "method" , $data[ 1 ] ) -> delete( );
            $response[ "success"]   =   1;
        }

        return response()->json($response);
    }

    /**
     * This will install a module from a ZIP FILE. It needs to have an special structure to make it work
     */
    public function installFromZip(){
        $data = [];
        return view("main.module.from_zip") -> with( $data );
    }


}