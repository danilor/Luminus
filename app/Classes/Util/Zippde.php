<?php

namespace App\Classes\Util;
use App\Classes\System\SystemError;
use App\Classes\System\SystemException;
use Dompdf\Exception;

/**
 * Class Zippde
 * This class will manage everything about creating zips in the system. It will use any available
 * It needs the ZipArchive library to work. If the library is not present,
 * once the object has been constructed it will throw an error.
 * library we can find.
 * @package App\Classes\Util
 * @see \ZipArchive
 * @link http://php.net/manual/en/class.ziparchive.php
 *
 */
class Zippde
{

    const DEFAULT_EXTENSION = '.zip';
    /**
     * @var string
     */
    private $extension = '';
    /**
     * The filename we want to generate
     * @var string
     */
    private $filename = '';
    /**
     * The path where the file will be stored
     * @var string
     */
    private $path = '';


    /**
     * Indicates if we want to overwrite the file
     * @var bool
     */
    private $overwrite = false;

    /**
     * This variable will hold the ZipArchive information
     * @var null
     */
    private $ZipArchive = null;


    /**
     * The list of directories we want to use
     * @var array
     */
    private $directories = [];

    /**
     * The list of files we want to use
     * @var array
     */
    private $files = [];


    /**
     * Zippde constructor.
     * @param string $filename
     * @param string $path
     * @param bool $overwrite
     * @throws \Error
     */
    public function __construct($filename, $path, $overwrite = true)
    {
        /**
         * Lets check first if the extension is loaded
         */
        if( !extension_loaded( "zip" ) || !class_exists("ZipArchive") ){
            dd( "Not loaded" , get_loaded_extensions() );
            throw new \App\Classes\System\SystemError("Extension ZipArchive required for this class to work. If you are working with Linux please run 'sudo apt-get install php7.0-zip' to install it.");
        }
        $this   ->  filename            =   $filename;
        $this   ->  path                =   $path;
        $this   ->  overwrite           =   $overwrite;
        $this   ->  extension           =   self::DEFAULT_EXTENSION;
        $this->setZipArchive( new \ZipArchive() );
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function shouldOverwrite(): bool
    {
        return $this->overwrite;
    }

    /**
     * @param bool $overwrite
     */
    public function setOverwrite(bool $overwrite)
    {
        $this->overwrite = $overwrite;
    }

    /**
     * @return \ZipArchive
     */
    public function getZipArchive() : \ZipArchive
    {
        return $this->ZipArchive;
    }

    /**
     * @param \ZipArchive $ZipArchive
     */
    public function setZipArchive(\ZipArchive $ZipArchive)
    {
        $this->ZipArchive = $ZipArchive;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension(string $extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return array
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }

    /**
     * @param array $directories
     */
    public function setDirectories(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }


    /**
     * Adds a single file
     * @param string $file
     */
    public function addFile(string $file ){
        $this -> files[] = $file;
    }


    /**
     * Adds a single directory
     * @param string $dir
     * @param null $subfolder If indicated, we are going to use a subfolder string. Cannot be numeric
     */
    public function addDirectory(string $dir , $subfolder = null ){
        if( $subfolder != null ){
            $this -> directories[ $subfolder ] = $dir;
        }else{
            $this -> directories[] = $dir;
        }
    }

    /**
     * This method will execute the ZIP process for this class.
     * @link  http://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php;
     */
    public function executeZip(){

        // Initialize archive object
        $this -> ZipArchive = new \ZipArchive();

        $final_path = $this->getPath() . DIRECTORY_SEPARATOR . $this->getFilename() . $this->extension;

        $file_status = false;

        try{
            if( $this->shouldOverwrite() ){
                $file_status = $this -> ZipArchive -> open($final_path , \ZipArchive::CREATE | \ZipArchive::OVERWRITE );
            }else{
                $file_status = $this -> ZipArchive -> open($final_path , \ZipArchive::CREATE );
            }
        }catch (\Exception $err){
            throw new SystemError( $err->getMessage() );
        }

        if( !$file_status ){
            throw new SystemError( "ZipArchive OPEN could not create the temporal file" );
        }


        /**
         * First lets just add each individual file
         */
        foreach( $this -> getFiles() AS $file ){
            $this -> ZipArchive -> addFile($file, basename($file) );
        }

        /**
         * Now the important part, the directories (folder)
         */

        foreach( $this->getDirectories() AS $sub => $directory ){
            $files = [];

            try{
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator( $directory ),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );
            }catch (\Exception $err){
                /**}
                 * If there is any error, then just skip
                 */
                $files = [];
            }

            foreach ($files as $name => $file)
            {
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();

                    if( is_numeric( $sub ) ){
                        $relativePath = substr($filePath, strlen( $directory ) + 1 );
                    }else{
                        $relativePath = $sub .  substr($filePath, strlen( $directory ) );
                    }

                    //$relativePath = substr($filePath, strlen( $directory ) + 1 );
                    // Add current file to archive
                    $this -> ZipArchive -> addFile($filePath, $relativePath);
                }
            }
        }

        // Zip archive will be created only after closing object
        try{
            $this -> ZipArchive -> close();
        }catch (\Exception $err){
            //dd( $this -> files , $this -> ZipArchive , $final_path , $err->getMessage() );
            throw new SystemError( $err->getMessage() );
        }


        return $this->getFilename();

    }

    /**
     * This fucntion will return the full path location for the file
     * @return string
     */
    public function getPathLocation() : string{
        return $this->getPath() . DIRECTORY_SEPARATOR . $this->getFilename() .   $this->getExtension();
    }

    /**
     * This function will extract the file into the path specified. If there is no path specified then it will extract it right next to the original file
     * @param string $path
     * @return string
     * @throws \Exception
     */
    public function extract( string $path = null ) : string{
        $extract_path = $this->getPath() . DIRECTORY_SEPARATOR . $this->filename;
        if( $path != "" ){
            $extract_path =  $path;
        }
        try{
            $this->getZipArchive()->open( $this->getPathLocation() );
            $this->getZipArchive()->extractTo($extract_path);
        }catch (SystemException $err){
            throw new \Exception( "Could not extrat the file. " , $err );
        }
        return $extract_path;

    }




}