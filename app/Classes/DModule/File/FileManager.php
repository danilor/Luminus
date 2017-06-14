<?php
namespace App\Classes\DModule\File;

use App\Classes\System\SystemException;
use App\Classes\Util\ExcelFile;
use App\ModuleDocument;


/**
 * Class DModuleFileManager
 *
 * This class will manage every work related for the file uploads for all modules.
 *
 * Esta clase se encarga de manejar todo el trabajo relacionado para los módulos y la subida de archivos
 *
 * @package App\Classes\DModule
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class FileManager
{
    const MODULE_PATH = 'module_documents';
    /**
     * This indicates the time we want to keep a file alive. For now, only 60 minutes.
     *
     * Esto indica el tiempo que queremos mantener el archivo vivo. Por ahora, el valor es de 60 minutos.
     *
     * @var int
     */
    public static $maxFileTime = 60; // Minutes

    /**
     * This will be the list of attempted files. At the end I will loop
     * through this array and delete the registries of all this files.
     * if a file is keep in the registry for more than (1 hour) it will be fully deleted
     *
     * Esta va a ser la lista de archivos en proceso de intento. Al final del proceso, yo voy a recorrer
     * todo el arreglo y borrar los registros de los archivos.
     * Si un registro de un archivo se mantiene por más de 1 hora, será automáticamente borrado.
     * @var array
     */
    private $attemptFiles = [];

    private $module_name = "";

    /**
     * FileManager constructor.
     * @param string $module_name
     */
    public function __construct($module_name)
    {
        $this->module_name = $module_name;
    }


    /**
     * it returns the file object for the key indicated.
     * If fails, it should return a System error
     *
     * Regresa el objeto File para llave indicada.
     * Si falla, debería devolver un error de sistema
     *
     * @param string $keyName
     * @return File
     * @throws SystemException If the file doesn't exist or cannot be found it will throw this error.
     * @todo Complete the function | Completar la función
     */
    public function getModuleFile(string $keyName ) : File
    {
        // TODO complete function
    }

    /**
     * This method will make an attempt to upload a file. It will actually upload it and save it into a table that will keep it in record.
     *
     * Este método va a realizar el intento de subir un archivo. Éste va a, de hecho, subir el archivo y salvar el registro en una tabla
     *
     * @param FileAttempt $FileAttempt
     * @return File
     * @throws \Exception If any error happens
     * @throws \Error If there is any error.
     */
    public function attemptFileUpload(FileAttempt $FileAttempt ) : File
    {
        $FileSystem = \Request::file( $FileAttempt->getKeyName() );
        $ext = strtolower(\File::extension( $FileSystem ->getClientOriginalName() ) );
        $mime = $FileSystem -> getMimeType();
        $FileRegistry = new ModuleDocument();
        $FileRegistry -> module     =   $this   ->  module_name;
        $FileRegistry -> name       =   $FileAttempt   ->  getAutoName();
        $FileRegistry -> key        =   $FileAttempt   ->  getKeyName();
        $FileRegistry -> mime       =   $mime;
        $FileRegistry -> extension  =   $ext;
        $FileRegistry -> active     =   1;

        /**
         * Lets check if the memory has the file
         *
         * Verificamos si la memoria contiene el archivo
         *
         */
        if( $FileSystem == null ){
            throw new \Error( "File could not be found in the memory: " .  $FileAttempt -> getKeyName() );
        }

        /**
         * Now, we verify if the path is right
         *
         * Ahora, verificamos que la ruta sea la correcta
         *
         */
        $path = $this -> verifyModuleFolder();
        if( $path == "" ){
            throw new \Error( "Could not create the module document directory" .  $FileAttempt -> getKeyName() );
        }
        /**
         * Now we have to verify that the extension is valid, but only
         * if there were extensions indicated
         */
         if( $FileAttempt->getExtensions() != null && count( $FileAttempt->getExtensions() ) > 0 ){
            // We have to verify the extension
            if( !in_array( $ext , $FileAttempt->getExtensions() ) ){
                throw new \Exception( "Invalid extension");
            }
         }

        /**
         * Now lets verify that the file is in the right size
         */
         if( (int)$FileAttempt->getMaxFileSize() > 0 ){
            //This means we have to verify
            if( (float)$FileSystem->getSize() > (float)$FileAttempt->getMaxFileSize() ){
                throw new \Exception( "File too large. Max file size is: " . (float)$FileAttempt->getMaxFileSize() . ' '  );
            }
         }


        $systemName = $FileAttempt->getAutoName() . '.' . $ext;
        try{
            $FileSystem -> move( $path , $systemName );
        }catch (\Exception $err){
            throw new \Exception( "Exception trying to move the file to the server" );
        }catch (\Error $err){
            throw new \Exception( "Error trying to move the file to the server" );
        }
        $File = new File( $FileAttempt->getAutoName() , $ext , 0 , $path , new \DateTime() , $mime );
        $FileRegistry -> real_path     =   $path;
        $FileRegistry -> save();


        $this -> addNewFileRegistry( $File->getKeyName() );
        return $File;
    }

    /**
     * This method will make an attempt to upload a file. It will actually upload it and save it into a table that will keep it in record.
     *
     * Este método va a realizar el intento de subir un archivo. Éste va a, de hecho, subir el archivo y salvar el registro en una tabla
     *
     * @param FileAttempt $FileAttempt
     * @param $path The path where we want to store our file | La ruta de donde queremos almacenar nuestro archivo
     * @return File
     * @throws \Error If there is any error.
     * @throws \Exception If any error happens
     */
    public function attemptFileUploadCustom(FileAttempt $FileAttempt, $path ) : File
    {
        $FileSystem = \Request::file( $FileAttempt->getKeyName() );
        $ext = strtolower(\File::extension( $FileSystem ->getClientOriginalName() ) );
        $mime = $FileSystem -> getMimeType();

        /**
         * Lets check if the memory has the file
         *
         * Verificamos si la memoria contiene el archivo
         *
         */
        if( $FileSystem == null ){
            throw new \Error( "File could not be found in the memory: " .  $FileAttempt -> getKeyName() );
        }

        /**
         * Now we have to verify that the extension is valid, but only
         * if there were extensions indicated
         */
        if( $FileAttempt->getExtensions() != null && count( $FileAttempt->getExtensions() ) > 0 ){
            // We have to verify the extension
            if( !in_array( $ext , $FileAttempt->getExtensions() ) ){
                throw new \Exception( "Invalid extension");
            }
        }

        /**
         * Now lets verify that the file is in the right size
         */
        if( (int)$FileAttempt->getMaxFileSize() > 0 ){
            //This means we have to verify
            if( (float)$FileSystem->getSize() > (float)$FileAttempt->getMaxFileSize() ){
                throw new \Exception( "File too large. Max file size is: " . (float)$FileAttempt->getMaxFileSize() . ' '  );
            }
        }
        $systemName = $FileAttempt->getAutoName() . '.' . $ext;
        try{
            $FileSystem -> move( $path , $systemName );
        }catch (\Exception $err){
            throw new \Exception( "Exception trying to move the file to the server" );
        }catch (\Error $err){
            throw new \Exception( "Error trying to move the file to the server" );
        }
        $File = new File( $FileAttempt->getAutoName() , $ext , 0 , $path , new \DateTime() , $mime );
        return $File;
    }


    /**
     * This method will analize an excel file and return the stdClass with miexed information
     *
     * Este método analiza un archivo excel y regresa el stdClass con información mezclada
     *
     * @param FileAttempt $FileAttempt
     * @return ExcelFile
     * @throws \Exception If any error happens
     * @throws \Error If there is any error.
     */
    public function analyzeFileExcel(FileAttempt $FileAttempt ) : ExcelFile
    {
        $FileSystem = \Request::file( $FileAttempt->getKeyName() );
        $ext = strtolower(\File::extension( $FileSystem ->getClientOriginalName() ) );
        /*$mime = $FileSystem -> getMimeType();
        $FileRegistry = new ModuleDocument();
        $FileRegistry -> module     =   $this   ->  module_name;
        $FileRegistry -> name       =   $FileAttempt   ->  getAutoName();
        $FileRegistry -> key        =   $FileAttempt   ->  getKeyName();
        $FileRegistry -> mime       =   $mime;
        $FileRegistry -> extension  =   $ext;
        $FileRegistry -> active     =   1;
*/
        /**
         * Lets check if the memory has the file
         */
        if( $FileSystem == null ){
            throw new \Error( "File could not be found in the memory: " .  $FileAttempt -> getKeyName() );
        }

        /**
         * Now, we verify if the path is right
         */
        /*$path = $this -> verifyModuleFolder();
        if( $path == "" ){
            throw new \Error( "Could not create the module document directory" .  $FileAttempt -> getKeyName() );
        }*/

        /**
         * Now we have to verify that the extension is valid, but only
         * if there were extensions indicated
         */
         if( $FileAttempt->getExtensions() != null && count( $FileAttempt->getExtensions() ) > 0 ){
            // We have to verify the extension
            if( !in_array( $ext , $FileAttempt->getExtensions() ) ){
                throw new \Exception( "Invalid extension");
            }
         }

        /**
         * Now lets verify that the file is in the right size
         */
         if( (int)$FileAttempt->getMaxFileSize() > 0 ){
            //This means we have to verify
            if( (float)$FileSystem->getSize() > (float)$FileAttempt->getMaxFileSize() ){
                throw new \Exception( "File too large. Max file size is: " . (float)$FileAttempt->getMaxFileSize() . ' '  );
            }
         }

        /**
         * Now we have to make the analysis
         */

        try{
            $ExcelFile = ExcelFile::analyseExcel( $FileSystem->getRealPath() );
        }catch (\Exception $err){
                throw new \Exception( "Exception reading and analysing the file"  );

        }catch (\Error $err){
                throw new \Error( "Error reading and analysing the file"  );
        }
        return $ExcelFile;
    }

    /**
     * This method will add the registry to the list.
     *
     * Este método va a añadir un registro a la lista
     *
     * @param string $reg
     */
    private function addNewFileRegistry(string $reg ){
        $this -> attemptFiles[] = $reg;
    }


    /**
     * This method will verify the folder of the module
     * and create it if it does not exist.
     *
     * Este método va a verificar la existencia del folder del módulo
     * y crearla si fuera el caso de que no existe
     *
     * @return string
     */
    private function verifyModuleFolder() : string
    {
        $path = storage_path( self::MODULE_PATH . "/" . $this->module_name );
        if( \File::exists( $path )  && \File::isDirectory( $path )){
            return $path;
        }
        try{
            \File::makeDirectory( $path , 0777 , true );
        }catch (\Error $err){}catch(\Exception $err){} //We dont really care if it could not create it
        return $path;
    }

    /**
     * This method will go through the last attempt registries and attempt
     * to delete them.
     *
     * Este método va a ir a través de los intentos anterios e intentar borrarlos.
     *
     * @return bool
     */
    public function completeAttempts(  ) : bool
    {
        //dd( $this->attemptFiles , $this->getModuleName() );
        \DB::table( "module_documents" )
            -> where( "module" , $this->getModuleName() )
            -> whereIn( "name" , $this->attemptFiles )
            -> update( [ "active" => 0 , "updated_at" => new \DateTime() ] )
        ;
        return true;
    }

    /**
     * Gets the module name
     *
     * Obtiene el nombre del módulo
     *
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->module_name;
    }

    /**
     * Sets the module name
     *
     * Establece el nombre del módulo
     *
     * @param string $module_name
     */
    public function setModuleName(string $module_name)
    {
        $this->module_name = $module_name;
    }

    /**
     * This method deletes a file from the module documents
     *
     * Este método borra un archivo de los documentos del módulo
     *
     * @param string $key
     * @return bool
     * @throws \Error
     */
    public function deleteDocument(string $key ) : bool
    {

        $path = $this -> verifyModuleFolder();
        if( $path == "" ){
            throw new \Error( "Could not create the module document directory" .  $key );
        }

        $file = $path . '/' . $key;
        if( \File::exists( $file ) ){
            \File::delete( $file );
            return true;
        }else{
            return false;
        }
    }



    /**
     * *********************************************
     * Static methods
     * *********************************************
     */


    /**
     * This method wil go through all registries in the database, and with those that are older than the time
     * indicated will be deleted from the hard drive and from the database.
     * This method should be called at least once per hour.
     *
     * Este método va a ir a través de todos los registros en la base de datos, y con aquellos que son más antiguos que el tiempo
     * indicado van a ser borrados del disco duro y de la base de datos.
     * Este método debería ser llamado al menos una vez por hora
     */
     public static function cleanFilesRegistries(){

        $table = "module_documents";
        $search_dated = new \DateTime();
        $search_dated -> modify( "- " . self::$maxFileTime . 'minutes' );

        $res = \DB::table( $table ) -> where( "created_at" , '<' , $search_dated ) -> where( "active" , 1 ) -> get() ;
        foreach( $res AS $r ){
            try{
                $path = rtrim( $r -> real_path , '/' ) . '/' . $r -> name . '.' . $r -> extension;
                \File::delete( $path );

                \DB::table( $table ) -> where( "id" , $r->id ) -> delete();
            }catch (\Error $err){} catch(\Exception $err){}
        }

        $res = \DB::table( $table ) -> where( "active" , 0 ) -> delete() ;
     }




}