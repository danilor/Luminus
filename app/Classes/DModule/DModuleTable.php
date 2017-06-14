<?php

declare(strict_types=1);

namespace App\Classes\DModule;
use Dompdf\Exception;

/**
 * Class DModule_Table
 *
 * This is the definition of each table for the modules.
 * Please, don't use names of more than 42 characters for the tables.
 * Its a restriction according to https://dev.mysql.com/doc/refman/5.7/en/identifiers.html (64 characters)
 * and we need to work with some extra characters.
 *
 * Esta clase funciona como definición para cada tabla para los módulos.
 * Por favor no usar nombres de más de 42 caracteres para las tablas,
 * esto debido a una restricción de acuerdo a: https://dev.mysql.com/doc/refman/5.7/en/identifiers.html (64 caracteres)
 * y es necesario trabajar con cierta cantidad de extra caracteres
 *
 * @package App\Classes
 * @link https://dev.mysql.com/doc/refman/5.7/en/identifiers.html
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */


class DModuleTable
{
    private $name;
    private $fields = [];
    private $table_name_lenght = 42;

    /**
     * This variable will hold the list of index to create
     *
     * Esta variable va a contener la lista de los index creados
     *
     * @var array
     */
    private $index = [];
    private $index_drop = [];


    /**
     * This variable will hold the information for the foreign keys we want to use
     *
     * Esta variable va a contener la información de las llaves foráneas que queremos usar
     *
     * @var array
     */
    private $foreign = [];
    private $foreign_drop = [];


    /**
     * This will store the list of unique fields for this table
     *
     * Esta va a almacenar la lista de los campos "unique" para esta tabla
     *
     * @var array
     */
    private $unique_fields = [];
    private $unique_fields_drop = [];

    /**
     * This variable will store the list of primary keys for the table
     *
     * Esta variable va a almacenar la lista de llaves primarias para la tabla
     *
     * @var array
     */
    private $primary_keys = [];
    private $primary_keys_to_drop = [];

    /**
     * This are the available types for the fields
     *
     * Esta variable muestra los tipos de campos disponibles
     *
     * @var array
     */
    public static $available_types = ["string","text","longText","float","integer","tinyInteger","bigInteger","bigIncrements","increments","date","dateTime","drop","boolean"];

    /**
     * This method get the name of the table
     *
     * Este método obtiene el nombre de la tabla
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * This sets the name of the Table
     *
     * Establece el nombre de la tabla
     *
     * @param string $name
     * @throws \Exception If the name is longer than the character limit for the name it throws an error | Si el largo del nombre es mayor al limite permitido, la función devuelve un error
     */
    public function setName(string $name)
    {
        if( strlen( $name ) > $this -> table_name_lenght ){
            throw new \Exception( "Name cannot be more than 42 characters" );
        }
        $this->name = $name;
    }

    /**
     * This method will add a new field. It will return a boolean indicating if the field was added or not (depending on the type).
     *
     * Este método va a añadir un nuevo campo. Va a devolver un valor boleano indicando si el campo fue añadido o no (dependiendo del tipo).
     *
     * @param string $name
     * @param string $type
     * @param bool $required
     * @param int $length
     * @return bool The result of the addition
     */
    public function addField(string $name , string $type , bool $required = false , int $length = 256  ) : bool
    {
        //We need to check if the type is valid

        if( !in_array( $type , DModuleTable::getAvaliableTypes() ) ){
            return false; //If the field is not valid, then we return false
        }

        $f = new \stdClass();
        $f -> name      = $name;
        $f -> type      = $type;
        $f -> required  = $required;
        $f -> length    = $length;

        $this -> fields[] = $f; // Lets add the field type

        return true;
    }


    /**
     * Add a field of type bigIncrements
     *
     * Añade un campo tipo bigIncrements
     *
     * @param string $name
     * @param bool $required
     * @return bool
     * @see DModuleTable::addField()
     * @source
     */
    public function addBigIncrements(string $name , bool $required = false ) : bool
    {
        return $this -> addField( $name , "bigIncrements" , $required );
    }

    /**
     * Add a field of type bigInteger
     *
     * Añade un campo de tipo bigInteger
     *
     * @param string $name
     * @param bool $required
     * @return bool
     * @see DModuleTable::addField()
     * @source
     */
    public function addBigInteger(string $name , bool $required = false ) : bool
    {
        return $this -> addField( $name , "bigInteger" , $required );
    }



    /**
     * Add a field of type tinyInteger
     *
     * Añade un campo de tipo tinyInteger
     *
     * @param string $name
     * @param bool $required
     * @return bool
     * @see DModuleTable::addField()
     * @source
     *
     */
    public function addTinyInteger(string $name , bool $required = false ) : bool
    {
        return $this -> addField( $name , "tinyInteger" , $required );
    }

    /**
     * Add a field of type string
     *
     * Añade un campo de tipo string
     *
     * @param string $name
     * @param bool $required
     * @param int $length
     * @return bool
     * @see DModuleTable::addField()
     * @source
     */
    public function addFloat(string $name , bool $required = false , int $length = 255 ) : bool
    {
        return $this -> addField( $name , "float" , $required , $length );
    }

    /**
     * Add a field of type integer
     *
     * Añade un campo de tipo integer
     *
     * @param string $name
     * @param bool $required
     * @return bool
     * @see DModuleTable::addField()
     * @source
     */
    public function addInteger(string $name , bool $required = false ) : bool
    {
        return $this -> addField( $name , "integer" , $required );
    }

    /**
     * Add a field of type date
     *
     * Añade un campo de tipo date
     *
     * @param string $name
     * @param bool $required
     * @return bool
     * @see DModuleTable::addField()
     * @source
     */
    public function addDate(string $name , bool $required = false ) : bool
    {
        return $this -> addField( $name , "date" , $required );
    }

    /**
     * Add a field of type dateTime
     *
     * Añade un campo de tipo dateTime
     *
     * @param string $name
     * @param bool $required
     * @return bool
     * @see DModuleTable::addField()
     * @source
     */
    public function addDateTime(string $name , bool $required = false ) : bool
    {
        return $this -> addField( $name , "dateTime" , $required );
    }
    /**
     * Add a field of type boolean
     *
     * Añade un campo de tipo boleano
     *
     * @param string $name
     * @param bool $required
     * @return bool
     * @see DModuleTable::addField()
     * @source
     */
    public function addBoolean(string $name , bool $required = false ) : bool
    {
        return $this -> addField( $name , "boolean" , $required );
    }

    /**
     * Add a field of type string
     *
     * Añade un campo de tipo string
     *
     * @param string $name
     * @param bool $required
     * @param int $length
     * @return bool
     * @see DModuleTable::addField()
     * @source
     */
    public function addString(string $name , bool $required = false , int $length = 255 ) : bool
    {
        return $this -> addField( $name , "string" , $required , $length );
    }

    /**
     * Add a field of type text
     *
     * Añade un campo de tipo text
     *
     * @param string $name
     * @param bool $required
     * @param int $length
     * @return bool
     * @see DModuleTable::addField()
     * @source
     */
    public function addText(string $name , bool $required = false , int $length = 255 ) : bool
    {
        return $this -> addField( $name , "text" , $required , $length );
    }

    /**
     * Add a field of type longText
     *
     * Añade un campo de tipo longText
     *
     * @param string $name
     * @param bool $required
     * @param int $length
     * @return bool
     * @see DModuleTable::addField()
     * @source
     */
    public function addLongText(string $name , bool $required = false , int $length = 255 ) : bool
    {
        return $this -> addField( $name , "longText" , $required , $length );
    }


    /**
     * It deletes a column
     *
     * Borra una columna
     *
     * @param string $name
     * @return bool
     */
    public function dropColumn(string $name ) : bool
    {
        return $this -> addField( $name , "drop" , true , 0 );
    }

    /**
     * This function return the list of the fields in stdClass
     *
     * Esta función devuelve la lista de campos como un stdClass
     *
     * @return array
     * @see \stdClass
     */
    public function getFieldsList() : array
    {
        return (array) $this -> fields;
    }

    /**
     * This method clears the fields list
     *
     * Este método limpia la lista de campos
     *
     */
    public function clearFieldsList(){
        $this -> fields = [];
    }

    /**
     * This function returns the list of available types
     *
     * Esta función regresa la lista de tipos disponibles de campos
     *
     */

     public static function getAvaliableTypes() : array
    {
        return (array)self::$available_types;
    }

    /**
     * It returns the list of index
     *
     * Regresar la lista de los index
     *
     * @return array
     */
    public function getIndex(): array
    {
        return $this->index;
    }

    /**
     * It returns the list of index to drop
     *
     * Devuelve la lista de los index para botar
     *
     * @return array
     */
    public function getIndexDrop(): array
    {
        return $this->index_drop;
    }
    /**
     * @return array
     */
    public function getForeign(): array
    {
        return $this->foreign;
    }

    /**
     * It returns the list of foreign keys to drop
     *
     * Devuelve la lista de foreign keys para eliminar
     *
     * @return array
     */
    public function getForeignDrop(): array
    {
        return $this->foreign_drop;
    }

    /**
     * Adds a single index
     *
     * Añade un único index
     *
     * @param string $index_column
     * @param string $index_name
     */
    public function addIndex( string $index_column , string $index_name ){
        $this -> index[] = [ "column" => $index_column , "name" => $index_name ];
    }


    /**
     * Adds a single index to drop
     *
     * Añade un único index para borrar
     *
     * @param string $index_name
     */
    public function addIndexToDrop(  string $index_name ){
        $this -> index_drop[] = $index_name;
    }


    /**
     * This method will add a foreign key to the table
     *
     * Este método va a añadir una llave foránea a la tabla
     *
     * @param mixed $columns This can be array of string. If array, the foreign will be made of multiple columns, if string, it will be only one
     * @param string $name
     * @param string $foreign_table
     * @param string $foreign_column
     * @param bool $own Indicates if the table is one of the same module or not
     */
    public function addForeign( $columns , string $name , string $foreign_table , string $foreign_column , bool $own = true  ){
        $this -> foreign[] = [
            "columns"       =>      $columns,
            "name"          =>      $name,
            "table"         =>      $foreign_table,
            "foreign_column"=>      $foreign_column,
            "own"           =>      $own
        ];
    }

    /**
     * Adds a Foreign key to be dropped
     *
     * Añade una llave foránea para ser borrada
     *
     * @param string $name
     */
    public function addForeignToDrop(string $name ){
        $this -> foreign_drop[] = $name;
    }

    /**
     * Gets the unique fields
     *
     * Obtiene la lista de campos únicos
     *
     * @return array
     */
    public function getUniqueFields(): array
    {
        return $this->unique_fields;
    }

    /**
     * Sets the unique fields
     *
     * Establece los campos únicos
     *
     * @param array $unique_fields
     */
    public function setUniqueFields(array $unique_fields)
    {
        $this->unique_fields = $unique_fields;
    }

    /**
     * This method adds a single unique field.
     * The unique field can be string or array. If it is an array it will create a compound unique field
     *
     * Este método añade un campo único.
     * El campo único puede ser tanto un string o un arreglo, si es un arreglo crea un campo único compuesto
     *
     * @param mixed $unique
     * @param string $name
     */
    public function addUniqueField( $unique ,  string $name ){
        $this -> unique_fields[] = [ "field" => $unique , "name" => $name ];
    }

    /**
     * @return array
     */
    public function getPrimaryKeys(): array
    {
        return $this->primary_keys;
    }

    /**
     * @param array $primary_keys
     */
    public function setPrimaryKeys(array $primary_keys)
    {
        $this->primary_keys = $primary_keys;
    }


    /**
     * This method will add a single primary key to the list. It can be called as many times as
     * the user wants and will add as many primary keys to the table.
     * NOTE: The fields of bigIncrements and increments are automatically added as primary keys.
     * @param string $key
     */
    public function addPrimaryKey(string $key ){
        $this -> primary_keys[] = $key;
    }

    /**
     * Gets the unique fields to drop
     *
     * Obtiene la lista de campos únicos para elimiar
     *
     * @return array
     */
    public function getUniqueFieldsDrop(): array
    {
        return $this->unique_fields_drop;
    }

    /**
     * Sets the unique fields to drop
     *
     * Establece la lista de campos únicos para eliminar
     *
     * @param array $unique_fields_drop
     */
    public function setUniqueFieldsDrop(array $unique_fields_drop)
    {
        $this->unique_fields_drop = $unique_fields_drop;
    }


    /**
     * Adds a single field to drop
     *
     * Añade un campo único para borrar
     *
     * @param string $field
     */
    public function addUniqueFieldToDrop(string $field ){
        $this -> unique_fields_drop[] = $field;
    }

    /**
     * Gets the list of primary keys to drop
     *
     * Obtiene la lista de llaves primarias para borrar
     *
     * @return array
     */
    public function getPrimaryKeysToDrop(): array
    {
        return $this->primary_keys_to_drop;
    }

    /**
     * Sets the primary keys to drop
     *
     * Establece las llaves primarias para eliminar
     *
     * @param array $primary_keys_to_drop
     */
    public function setPrimaryKeysToDrop(array $primary_keys_to_drop)
    {
        $this->primary_keys_to_drop = $primary_keys_to_drop;
    }


    /**
     * Adds a single primary key to drop
     *
     * Añade una llave primaria para ser eliminar.
     *
     * @param string $key
     */
    public function addPrimaryKeyToDrop(string $key ){
        $this -> primary_keys_to_drop[] = $key;
    }
}