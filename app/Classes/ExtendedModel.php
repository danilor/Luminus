<?php

namespace App\Classes;

use Illuminate\Database\Eloquent\Model;
/**
 * Class ExtendedModel
 *
 * This class should take the Model Class and add the functions we want to use for all Models.
 * We don't want to modify the MODEL class since its part of the Laravel instalation.
 *
 * Esta clase toma la clase "Model" de Laravel y añade funciones que queremos usar para todos los modelos existentes.
 * No queremos modificar la clase "Model" de laravel debido a que es parte de la instalación de Laravel.
 *
 * @package App\Classes
 */
abstract class ExtendedModel extends Model
{

    /**
     * This function returns any column date formatted according to the format configuration.
     *
     * Esta función regresa cualquier columna de fecha acorde al formato indicado en las configuraciones.
     *
     * @param string $field
     * @param bool $full If we want the full date of not
     * @return string
     */
    public function getFormattedDate(string $field , bool $full = false) : string
    {
        if( $full ){
            return date( config("formats.date_full") ,strtotime( $this->$field ) );
        }else{
            return date( config("formats.date_simple") ,strtotime( $this->$field ) );
        }
    }

    /**
     * This function returns the creation date formatted according to the format configuration.
     *
     * Esta función regresa la fecha de creación acorde al formato indicado en las configuraciones.
     *
     * @param bool $full If we want the full date of not
     * @return string
     */
    public function getCreationDate(bool $full = false) : string
    {
        if( $full ){
            return date( config("formats.date_full") ,strtotime( $this->created_at ) );
        }else{
            return date( config("formats.date_simple") ,strtotime( $this->created_at ) );
        }
    }


    /**
     * This function returns the creation date.
     *
     * Esta función regresar la fecha de creación.
     *
     * @return string
     */
    public function getCreationDateRaw(  ) : string
    {
        return $this -> created_at;
    }

    /**
     * This function returns the updated date formatted according to the format configuration.
     *
     * Esta función regresa la fecha de actualización acorde al formato configurado.
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
     * This function returns the updated date.
     *
     * Esta función retorna la fecha de actualización.
     *
     * @return string
     */
    public function getUpdatedDateRaw(  ) : string
    {
        return $this -> updated_at;
    }

    /**
     * This function returns the creation date in ISO 8601.
     *
     * Esta función regresa la fecha de creación en formato ISO 8601
     *
     * @return string
     */
    public function getCreationDateISO8601(  ) : string
    {
        return date( "c" ,strtotime( $this->created_at ) );
    }
    /**
     * This function returns the updated date in ISO 8601.
     *
     * Esta función regresa la fecha de actualización en formato ISO 8601.
     *
     * @return string
     */
    public function getUpdatedDateISO8601(  ) : string
    {
        return date( "c" ,strtotime( $this->updated_at ) );
    }

    /**
     * This function returns the creation date in UnixTime Stamp
     *
     * Esta función regresa la fecha de creación en formato de UnixTimeStamp
     *
     * @return string
     */
    public function getCreationDateUnixTimeStamp(  ) : string
    {
        return date( "U" ,strtotime( $this->created_at ) );
    }
    /**
     * This function returns the updated date in UnixTime Stamp
     *
     * Esta función regresa la fecha de actualización en formato de UnixTimeStamp
     *
     * @return string
     */
    public function getUpdatedDateUnixTimeStamp(  ) : string
    {
        return date( "U" ,strtotime( $this->updated_at ) );
    }
}