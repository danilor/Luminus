<?php

namespace App\Classes\DModule\File;

use App\Classes\System\SystemException;


/**
 * Class DModuleFile
 *
 * This class will contain all the object of the file retrieved. This wont have any "set" method because
 * it is suppose that the file cannot be modified.
 *
 * Esta clase va a contener el objeto del archivo leído. Esta clase no va a contener ningun método "set" (establecer) debido
 * a que se supone que los archivos no son modificables.
 *
 * @package App\Classes\DModule
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class File
{

    private $keyName = "";
    private $extension = "";
    private $size = 0.0;
    private $realLocation = "";
    private $created = null;
    private $mime = "";
    /**
     * Wont be necessary, but just in case.
     *
     * No es necesario, pero solo por si acaso.
     *
     * @var string
     */
    private $title = "";

    /**
     * DModuleFile constructor.
     * @param string $keyName
     * @param string $extension
     * @param float $size
     * @param string $realLocation
     * @param \DateTime $created
     */
    public function __construct( string $keyName , string $extension , float $size , string $realLocation , \DateTime $created , $mime = "")
    {
        $this->keyName      =   $keyName;
        $this->extension    =   $extension;
        $this->size         =   (float)$size;
        $this->realLocation =   $realLocation;
        $this->created      =   $created;
        $this->mime         =   $mime;
    }

    /**
     * Gets the name
     *
     * Obtiene el nombre
     *
     * @return string
     */
    public function getKeyName(): string
    {
        return $this->keyName;
    }

    /**
     * Gets the extension
     *
     * Obtiene la extensión
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Gets the size
     *
     * Obtiene el tamaño
     *
     * @return float
     */
    public function getSize(): float
    {
        return $this->size;
    }

    /**
     * Gets the real location
     *
     * Obtiene la ubicación real
     *
     * @return string
     */
    public function getRealLocation(): string
    {
        return $this->realLocation;
    }

    /**
     * Gets the created date
     *
     * Obtiene la fecha de creación
     *
     * @return \DateTime|null
     */
    public function getCreated()
    {
        return $this->created;
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
     * It returns the full filename
     * Regresa el nombre completo del archivo
     */
    public function getFullFilename(){
        return $this -> getKeyName() . '.' . $this -> getExtension();
    }

    /**
     * Gets the mime type
     *
     * Obtiene el tipo de mime del archivo
     * @return string
     */
    public function getMime(): string
    {
        return $this->mime;
    }


    public static function getFileObject( $path ) : File
    {
        if( !\File::exists( $path ) ){
            throw new SystemException('File does not exist');
        }

        $file = new \App\Classes\DModule\File\File( basename( $path ) , \File::extension($path) , 0 , $path , new \DateTime ,  \File::mimeType($path) );
        return $file;
    }

    public static function getDocumentFile( string $module , string $key ) : File
    {
        $path = storage_path( 'module_documents/' . $module . '/' . $key);
        try{
            return self::getFileObject( $path );
        }catch( SystemException $err ){
            throw $err;
        }

    }


}