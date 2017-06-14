<?php
declare(strict_types=1);
namespace App;

use App\Classes\Common;

use App\Classes\Email\Predefined\AvatarChanged;
use App\Classes\Email\Predefined\PasswordChanged;
use App\Classes\Email\Predefined\SuspiciousAttempts;
use App\Classes\System\SystemError;
use App\Classes\System\SystemException;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * Class User
 *
 * The User class (that inherits from the Authenticatable) is going to allow us to manage all aspects of the users from this same class without the need to make aditional queries to the database.
 *
 * La clase User (que extiende el Authenticatable) va a permitir manera todos los aspectos de los usuarios desde esta misma clase sin necesidad de realizar consultas adicionales a la base de datos.
 *
 * @package App
 * @version 1.0
 * @author: Danilo Ramírez Mattey
 */
class User extends Authenticatable
{

    use Notifiable;

    private $last_login_record_id = 0;


    /**
     * It sets up the minimun characters require for any new username that we want to set up
     * Indica cual es la cantidad mínima de caracteres para establecer un usuario nuevo.
     * @var int
     */
    const MIN_USERNAME = 6;

    const MIN_PASSWORD = 6;


    /**
     * The table where the sessions are being stored.
     *
     * La tabla donde las seciones están siendo almacenadas.
     *
     * @var string
     */
    private $table_sessions  = 'sessions';


    /**
     * This variable will hold the list of jobs of the user. I wont
     * load the jobs at the construction of the class
     * because not always is need it.
     *
     * Esta variable va a contener la lista de los trabajos del usuario. No voy a
     * cargar los trabajos en el constructor de la clase
     * debido a que no son necesarios todo el tiempo.
     *
     * @var string[]
     */
    private $jobs = null;

    /**
     * It returns if the user status is true or false.
     *
     * Regresa el estado del usuario.
     *
     * @return bool
     */
    public function getStatus() : bool
    {
        return (bool) $this->status;
    }

    /**
     * It returns if the user is administrator or not (bool).
     *
     * Regresa si el usuario es o no administrador (valor boleano).
     *
     * @return bool
     */
    public function isAdmin() : bool
    {
        return (bool) $this->administrator;
    }

    /**
     * Returns the full name of the user
     * @return string
     */
    public function getFullName() : string
    {
        return $this->firstname . " " . $this->lastname;
    }


    /**
     * This method will create a record login for this user.
     *
     * Este método va a crear un registro de ingreso para este usuario.
     *
     * @param string $extra
     */
    public function recordLogin(string $extra = "" ){
        $this -> last_login_record_id = Login::createNewLog( $this->id , $extra );
        $this -> lastLogin = new \DateTime();
    }


    /**
     * This method will record a logout.
     *
     * Este método va a registrar una salida del sistema del usuario.
     *
     * @param string $except If we want to delete all sesions except a specific one (for cases like login in from different devices) | Si queremos borrar todas las sesiones exceptuando alguna en específica (para los casos donde se registra ingreso desde distintos dispositivos).
     */
    public function closeAllActiveLogins($except = ""){
        try{
            \DB::table( $this->table_sessions )->where( "user_id" , $this -> id ) -> where( "id" ,'<>' , $except ) -> delete();
        }catch (\Error $err){ }catch(\Exception $err){ }

    }


    /**
     * This method will create a new log for this user.
     *
     * Este método va a crear un nuevo registro para este usuario
     *
     * @param string $action
     * @param string $description
     * @param int $eid1
     * @param int $eid2
     * @param int $eid3
     * @param int $eid4
     * @return int
     */
    public function logAction(string $action , string $description = "" , int $eid1 = 0 , int $eid2 = 0 , int $eid3 = 0 , int $eid4 = 0 ) : int
    {
        $l = new Log();
        $l -> user_id       =   $this -> id;
        $l -> session       =   \Session::getId();
        $l -> url           =   \Request::fullUrl();
        $l -> action        =   $action;
        $l -> description   =   $description;
        /**
         * This one, we are using the $_REQUEST because I don't know any
         * other way using laravel. In the future, if we found a way to get
         * all the request then we can use it. (BTW: Input::all()
         * doesn't work as we want >_< )
         * */
        $l -> request       =   serialize( $_REQUEST );
        $l -> external_id_1 =   $eid1;
        $l -> external_id_2 =   $eid2;
        $l -> external_id_3 =   $eid3;
        $l -> external_id_4 =   $eid4;

        $l -> save();
        /**
         * We return the just created ID (Just in case)
         */
        return (int) $l -> id;
    }


    /**
     * This method will load the jobs variable if it is empty.
     *
     * Este método va a cargar la lista de trabajos si fuera el caso de que la lista esté vacía.
     *
     */
    public function loadJobs(){
        if( $this->jobs != null ){
            return;
        }
        $res = \DB::table( "jobs_users" )
                ->leftJoin('jobs', 'jobs_users.job_id', '=', 'jobs.id')
                -> where("user_id" , $this  -> id)
                -> get();
        $aux = [];

        foreach( $res AS $r ){
            $aux[ (int)$r->id ] = $r -> name;
        }
        $this -> jobs = $aux;
    }


    /**
     * Returns true if the user has the indicated job (single one).
     *
     * Regresa -verdadero- si el usuario posee el trabajo indicado (verificación única).
     *
     * @param string $job The job we want to verify | El trabajo que queremos verificar
     * @return bool
     */
    public function hasJob(string $job) : bool
    {
        /**
         * If its administrator, then we don't care what Jobs it has. Just send true back!!!! Now!! >.<
         */
         if( $this -> isAdmin() ){
            return true;
         }
        $this -> loadJobs();
        return (bool)in_array( $job , $this  -> jobs );
    }

    /**
     * Returns the list of jobs.
     * For this case, with the user cannot set the list of jobs.
     *
     * Regresa la lista de trabajos.
     * Para este caso, contiene la lista de trabajos que el usuario no puede ver.
     *
     * @return \string[]
     */
    public function getJobs(): array
    {
        $this -> loadJobs();
        return $this->jobs;
    }


    /**
     * If returns true if any of the jobs indicated is in the list of
     * jobs for the user.
     *
     * Regresa verdadero si alguno de los trabajos indicados en la lista
     * fue encontrado para el usuario.
     *
     * @param array $jobs The list of jobs | La lista de trabajos
     * @return bool True if at least one job exist | Verdadero si al menos un trabajo es encontrado.
     */
    public function hasAnyJob(array $jobs ) : bool
    {
        /**
         * If its administrator, then we don't care what Jobs it has. Just send true back!!!! Now!! >.<
         */
         if( $this -> isAdmin() ){
            return true;
         }

        $this -> loadJobs();
        $has =  false;
        foreach( $jobs AS $entry_job ){
            if( in_array( $entry_job , $this->jobs ) ){
                $has = true;
            }
        }
        return $has;
    }

    /**
     * It returns true only if all the jobs indicated match the
     * job list of the user.
     *
     * Regresa verdadero solamente si todos los trabajos indicados concuerdan
     * con lal ista de trabajos del usuario.
     *
     * @param array $jobs The job list | TLa lista de trabajos.
     * @return bool
     */
    public function hasAllJob(array $jobs ) : bool
    {
        /**
         * If its administrator, then we don't care what Jobs it has. Just send true back!!!! Now!! >.<
         */
         if( $this -> isAdmin() ){
            return true;
         }
        $this -> loadJobs();
        $has =  true;
        foreach( $jobs AS $entry_job ){
            if( !in_array( $entry_job , $this->jobs ) ){
                $has = false;
            }
        }
        return $has;
    }

    /**
     * This function returns the creation date formatted according to the format configuration.
     *
     * Esta función regresa la fecha de creación con el formato indicado en las configuraciones.
     *
     * @param bool $full If we want the full date of not
     * @return string
     */
    public function getCreationDate(bool $full = false) : string
    {
        if( $full ){
            return $this->created_at->format( config("formats.date_full") );
        }else{
            return $this->created_at->format( config("formats.date_simple") );
        }
    }

    /**
     * This function returns the updated date formatted according to the format configuration.
     *
     * Esta función regresa la fecha de actualización con el formato indicado en las configuracinoes.
     *
     * @param bool $full If we want the full date of not
     * @return string
     */
    public function getUpdatedDate(bool $full = false) : string
    {
        if( $full ){
            return date( config("formats.date_full") ,strtotime( $this->updated_at ) );
        }else{
            return date( config("formats.date_simple") ,strtotime( $this->updated_at ) );
        }
    }


    /**
     * This method returns the - already processed - dashboard boxes from the modules.
     *
     * Este método regresa las -ya procesadas- cajas del panel principal.
     *
     * @return array The list of Module boxes | La lista de cajas de los módulos
     */
    public function getDashboardElements() : array
    {
        $aux = [] ;
        /**
         * We initialize the "boxes variable with the left and right keys at the beginning.
         */
        $boxes = [ "l" => [] , "r" => []];
        $aux_modules = [];
        /**
         * Lets get first all the dashboard modules added to this user.
         */
        $DashboardBoxes = $this -> getDashboardItems();

        /**
         * Second, I am interested on create all modules before using them.
         * This is because it can exist one case when a module has more than 1 dashboard item, so I dont want to load the module 1
         * time for each box, I want the same module to be used for all its boxes
         */
         $cont = 0; //My own counter
        foreach( $DashboardBoxes AS $DashboardBox ){
            /**
             * If the module has not being initialized, lets make it alive! Muahaha!
             * We are validating that the Module Object already exists because we don't want to
             * load it twice!
             */
            if( !isset( $aux_modules[ $DashboardBox->module ] ) ){
                try{
                    $aux_modules[ $DashboardBox->module ] = \App\Module::loadFullModuleDefinition( $DashboardBox->module );
                }catch ( SystemException $err){} catch(SystemError $err){} //I am not doing anything with the catch for now
            }
            /**
             * Now, lets see if the method actually exists (it has to start with "dashboard_"
             */
            try{
                /**
                 * It must return a DMOduleResponsePlain type object
                 */
                    $Widget = \App\Module::getModuleWidget( $aux_modules[ $DashboardBox->module ] , $DashboardBox -> method );
                    $boxes[ $DashboardBox->side ][ $cont ][ "content" ]   = $Widget -> getContent();
                    $boxes[ $DashboardBox->side ][ $cont ][ "id" ]        = $DashboardBox -> id;
                    $boxes[ $DashboardBox->side ][ $cont ][ "label" ]     = $DashboardBox -> label;
                    $boxes[ $DashboardBox->side ][ $cont ][ "title" ]     = $Widget->getTitle();
                    $cont ++;

            }catch(SystemError $err){}catch (SystemException $err){}
        }
        return $boxes;
    }

    /**
     * This method returns the list of registries of the UserDashboard widgets
     *
     * Este método devuelve la lista de los registros de los widgets del UserDashboard
     *
     * @return mixed
     */
    public function getDashboardItems(){
        return UserDashboard::where( "user_id" , $this->id ) // Only for this user
                            -> where( "status" , 1 ) // Where status is true (1)
                            -> orderBy( "order" , "asc" )  //And we are getting them by the order preference
                            -> get(  ) ;
    }

    /**
     * @return string
     */
    public function getAvatarPath(){
        return storage_path( config("filesystems.avatars") . "/" . $this->photo );
    }


    /**
     * This method returns the resource object of the avatar image for this user.
     *
     * Este método devuelve el objeto recurso de la imágen del avatar para este usuario.
     *
     * @param int $h The height. Default 200px | El alto. Predeterminado 200px
     * @param int $w The width. Default 200px | El ancho. Predeterminado 200px
     * @param string $fit The fit type we want to use (fit, resize, resizeCanvas,widen). Default: fit. | El tipo de ajuste que queremos usar (fit, resize, resizeCanvas,widen). Predeterminado: fit.
     * @return Response The resource with the image | El recurso con la imágen
     */

    public function getAvatar( int $h = 200 , int $w = 200 , $fit = 'fit') : Response
    {
        $path = $this -> getAvatarPath();

        if( empty($this -> photo) ){
            return \App\Classes\Site\Images::responseDefaultAvatar( $h , $w , $fit );
        }

        if( trim( $this -> photo ) === "" ){
            return \App\Classes\Site\Images::responseDefaultAvatar( $h , $w , $fit );
        }

        if( !\File::exists( $path ) ){
            return \App\Classes\Site\Images::responseDefaultAvatar( $h , $w , $fit );
        }

        return \App\Classes\Site\Images::getResizedImageResponse( $path , $h , $w , $fit );
    }

    /**
     * This method will return the path of the user avatar.
     * Este método va a devolver la ruta del avatar del usuario
     * @param int $h
     * @param int $w
     * @return string
     */
    public function getAvatarUrl(int $h = 200 , int $w = 200) : string
    {
        $path = config( "filesystems.url_avatar_base" ) . $this -> id . '?h=' . $h . '&w=' . $w;
        return $path;
    }


    /**
     * Creates and stores a new login attempt for this user
     * Crea y almacena un intento de ingreso para este usuario
     * @param bool $check If it is true, it will check for the amount of attempts in the pass hour and send an email if they seems suspicious | Si es verdadero, el sistema va a revisar la cantidad de intentos en la última hora y enviar un correo si se ven sospechosos.
     * @return int The registry ID | El ID del registro
     */
    public function storeLoginAttempt( $check = true){

        $la = new LoginAttempt();
        $la -> user_id      =       $this -> id;
        $la -> session_id   =       @session_id();
        $la -> ip           =       @\Request::ip( true );
        $la -> save();

        $last_id    =   $la -> id;

        /**
         * If check, then we are checking how many attempts were in the last hour, if it is equal or greater than the config limit, then we are sending an email
         */
        if( $check ){

            $max        =       (int)config("auth.max_attempts_per_hour");

            $hour_ago = new \DateTime( );
            $hour_ago -> modify( "-1 hour" );

            $attempts = LoginAttempt::where( "user_id" , $this -> id ) -> where( "user_id" , $this -> id) -> get();

            if( count( $attempts ) >= $max ){
                /**
                 * This means that we have execute more (failed) attempts in the last hour than the normal registry.
                 * We are going to send an email for that.
                 */

                $ips = [];
                foreach( $attempts AS $attempt ){
                    $ips[] = $attempt -> ip;
                }
                $ips = array_unique( $ips );


                try{
                    $email = new SuspiciousAttempts( $this , $ips );
                    $registry_id = $email -> queue();
                }catch (SystemError $err){
                    /**
                     * If there is an error we don't want to show any error in the site.
                     * because if there is somebody trying to access an account, then we
                     * don't want to give him more information of he/she already has
                     */
                }
            }
        }

        return $last_id;
    }


    /**
     * This method will change the user avatar
     * Este método va a cambiar el avatar del usuario
     * @param string $new_avatar
     */
    public function changeAvatar(string $new_avatar ){
        $this -> photo = $new_avatar;
        $this -> save();
        $fullUrl = config("app.url") .  $this -> getAvatarUrl();
        $email = new AvatarChanged( $this , $fullUrl , [ $this -> getAvatarPath() ] );
        $registry_id = $email -> queue();
    }


    /**
     * It tryies to change the username, or thorws an error if fails
     * Crata de cambiar el nombre de usuario, o tira error si falla.
     * @param string $username
     * @param bool $autosave
     * @return string
     * @throws SystemException
     */
    public function changeUsername(string $username , bool $autosave = true ){
    /**
     * First lets see if there is any other username with the same username
     */
        $user_same_username = User::where( "username" , $username ) -> where( "id" , "<>" , $this -> id) -> first();
        if( $user_same_username != null ){
            /**
            * This means that the username already exists
            */
            throw new SystemException('The username is already taken');
        }
        if( strlen( $username ) < self::MIN_USERNAME ){
            throw new SystemException('The username is too short');
        }

        if( $username != strtolower( Common::remove_accents(Common::transliterateString( $username ) ) ) ){
            /**
             * This means that it had special characters, so we are sending the error back
             */
            throw new SystemException('The username has special characters');
        }
        $this -> username = $username;
        if( $autosave ) $this -> save();
        return $username;
    }


    /**
     * @param string $password
     * @param string $password_confirm
     * @param bool $autosave
     * @return mixed
     * @throws SystemException
     */
    public function changePassword(string $password, string $password_confirm = '' , bool $autosave = true ){
        $fields[ "password" ] = $password;
        $rules[ "password" ] = Common::getValRule("password", true);
        if( $password_confirm != "" ){
            $rules[ "password" ] = Common::getValRule("password", true , 'confirmed');
            $fields[ "password_confirmation" ] = $password_confirm;
        }

        // run the validation rules on the inputs from the form
        $validator = \Validator::make( $fields , $rules);

        if ($validator->fails()) {
            throw new SystemException('The password failed the validation');
        }

        $this -> password = bcrypt( $password );

        $email = new PasswordChanged( $this );
        $registry_id = $email -> queue();

        if( $autosave ) $this -> save();

        return $registry_id;
    }

}
