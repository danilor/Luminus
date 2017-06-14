<?php


namespace App\Classes\Crontab;

use App\Classes\System\SystemError;
use App\Classes\System\SystemException;

/**
 * Class Cronjob
 * This class will try to work and manage everything about the cronjob in Linux Debian based systems.
 * It will require that the PHP have access to the curren crontab commands list
 *
 * Esta ckase va a tratar de trabajar y manejar todo lo referente a los cronjob en systemas Linux basados en Debian.
 * Va a requerir que el PHP tenga acceso a la lista de comandos del crontab
 *
 * @package App\Classes\Cronjob
 * @link http://www.computerhope.com/unix/ucrontab.htm
 * @link http://stackoverflow.com/questions/4421020/use-php-to-create-edit-and-delete-crontab-jobs
 * @link https://github.com/MediovskiTechnology/php-crontab-manager
 * @see \App\Classes\Crontab\Job
 */
class Crontab {

    /**
     * Indicates if we want to save a example file in the storage
     * Indica si queremos almacenar un archivo de ejemplo en el storage
     * @var bool
     */

    static $save_storage = true;
    static $storage_filename  = 'crontab.txt';

    static function getStorageFile(){
        return storage_path( "app/" . self::$storage_filename );
    }



    /**
     * Returns true if a cronjob exists
     * Regresa verdadero si un cronjob existe
     * @param string $command
     * @return bool
     * @throws SystemError
     * @throws SystemException
     */
    static function cronjob_exists(string $command) : bool{
        /**
         * Per default, the cronjob does not exist
         */
        $cronjob_exists = false;
        $crontab_text = '';
        try{
            $crontab_text = $command->getFrequency() . ' ' . $command -> getCommand() . PHP_EOL;
            /**
             * The $crontab variable doesnt seem to exist before this statement
             * because we are storing the crontab -l information into it. So it is being created on the fly
             */
            exec('crontab -l', $crontab);
            if(isset( $crontab )&&is_array( $crontab )){
                $crontab = array_flip( $crontab );
                if(isset( $crontab[$crontab_text]) ){
                    $cronjob_exists=true;
                }
            }
            return $cronjob_exists; // If it exist or not
        }catch( \Error $e ){
            throw new SystemError( $e -> getMessage() , $e -> getCode() );
        }catch( \Exception $e ){
            throw new SystemException( $e -> getMessage() , $e -> getCode() );
        }

    }


    /**
     * It will append a new cronjob to the current cronjob list
     * Va a añadir un nuevo cronjob a la lista de cronjob actuales
     * @param Job $command
     * @return array
     * @throws SystemError
     * @throws SystemException
     */
    static function append_cronjob(Job $command){
        $crontab_text = '';
        try{
            $crontab_text = $command->getFrequency() . ' ' . $command -> getCommand() . PHP_EOL;

            if( self::$save_storage ){
                \File::append( self::getStorageFile() , $crontab_text );
            }

            if( is_string( $command ) && !empty( $command )&& self::cronjob_exists( $command ) === FALSE ){
                // add job to crontab
                exec('echo -e "`crontab -l`\n'.$crontab_text.'" | crontab -', $output);
            }
        }catch( \Error $e ){
            throw new SystemError( $e -> getMessage() , $e -> getCode() );
        }catch( \Exception $e ){
            throw new SystemException( $e -> getMessage() , $e -> getCode() );
        }

    }

    /**
     * Deletes a cronjob
     * Elimina un cronjob
     * @param Job $command
     * @throws SystemError
     * @throws SystemException
     */
    static function remove_cronjob(Job $command){
        $crontab_text = '';
        try{
            $crontab_text = $command->getFrequency() . ' ' . $command -> getCommand() . PHP_EOL ;
            $contents = \File::get( self::getStorageFile() );
            $contents = str_replace( $crontab_text , "" , $contents );
            \File::put( self::getStorageFile() ,  $contents );
            exec('crontab -r', $crontab_text);
        }catch( \Error $e ){
            throw new SystemError( $e -> getMessage() , $e -> getCode() );
        }catch( \Exception $e ){
            throw new SystemException( $e -> getMessage() , $e -> getCode() );
        }
    }

}