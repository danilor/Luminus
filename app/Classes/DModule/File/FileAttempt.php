<?php

namespace App\Classes\DModule\File;

use App\Classes\Common;

/**
 * Class DModuleFile
 *
 * This class will work as an object for the file upload attempt.
 * Per security reasons, this class don't have the "set" functions for the attributes.
 * These attributes can be only modified in the constructor.
 *
 * Esta clase va a funcionar como un objeto para los intentos de subida de archivos.
 * Por motivos de seguridad, esta clase no tiene los métodos "set" (establecer) para sus atributos.
 * Estos atributos solo pueden ser establecidos con el constructor.
 *
 * @package App\Classes\DModule
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class FileAttempt
{
    /**
     * This is the key name of the field when its being uploaded. With this,
     * I mean that its the name of the field in the form.
     *
     * Esta es el nombre llave del campo que va a ser subido. Con esto
     * me refiero a que es el nombre del campo en el formulario
     *
     * @var string
     */
    private $keyName = "";
    private $extensions = [];
    private $maxFileSize = 0.0;
    private $prefix = "";

    private $autoName = "";


    /**
     * DModuleFile constructor.
     * @param string $keyName The keyname in the form | El nombre clave en el formulario
     * @param array $extensions The list of extensions to validate | La lista de extensiones para validar
     * @param float $maxFileSize The max file size to validate | El tamaño máximo del archivo para validar
     * @param string $prefix The file prefix to be stored | El prefijo del archivo para ser almacenado
     */
    public function __construct( string $keyName , array $extensions = [] , float $maxFileSize = 0.0 , string $prefix = "" )
    {
        $this->keyName = $keyName;
        $this->extensions = $extensions;
        $this->maxFileSize = $maxFileSize;
        $this->prefix = $prefix;
        $this -> autoName = $prefix . date( "Ymdhis" ) . \Session::getId() . Common::randomHex( 20 );
    }

    /**
     * Gets the Autogenerated Name
     *
     * Obtiene el nombre autogenerado
     *
     * @return string
     */
    public function getAutoName(): string
    {
        return $this->autoName;
    }

    /**
     * This method will overwrite the autoname we are generating. PLEASE USE WITH CAUTION
     * Este método va a sobreescribir el nombre automático que estamos generando. POR FAVOR USAR CON PRECAUCIÓN
     * @param string $name
     */
    public function overwriteAutoName(string $name){
        $this -> autoName = $name;
    }


    /**
     * Gets the keyname
     *
     * Obtiene el nombre llave del campo
     *
     * @return string
     */
    public function getKeyName(): string
    {
        return $this->keyName;
    }

    /**
     * Gets the Extensions
     *
     * Obtiene las extensionesk
     *
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * Gets the maxfile size
     *
     * Obtiene el tamaño máximo
     *
     * @return int
     */
    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    /**
     * Gets the prefix
     *
     * Obtiene el prefijo
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}