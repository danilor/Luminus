<?php
/**
 * Created by PhpStorm.
 * User: danilo
 * Date: 30/1/2017
 * Time: 10:00 AM
 */


namespace App\Classes;

use App\Classes\System\SystemException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Classes\DModule\DModuleTable;

/**
 * Class DynamicDatabase
 *
 * This class should help with everything related to create,
 * delete and update tables for the dynamic databases
 * (it means, for the modules that are actually dynamic)
 *
 * Esta clase debería de servir para ayudar a todo lo relacionado con la creación,
 * borrado y actualización de las tablas para el proceso de tablas dinámicas
 * (con esto se refiere, a los módulos que son de hecho dinámicos)
 *
 * @package App\Classes
 */
class DynamicDatabase
{

    /**
     * This method checks the existence of a table and return its existence as a boolean.
     *
     * Este método revisa la existencia de una tabla y retorna su existencia como un valor boleano.
     *
     * @param string $prefix
     * @param string $name
     * @return bool
     */
    public static function checkTable(string $prefix , string $name ) : bool
    {
        return ( Schema::hasTable( $prefix . $name ) );
    }

    /**
     * This method will create a table from a DModule_Table object, also, it will check if we can update the table.
     *
     * Este método va a crear una tabla a partir de un objeto DModule_Table. Además, va a revisar si se puede actualizar en vez de crear.
     *
     * @param string $prefix
     * @param DModuleTable $table
     * @return bool
     */
    public static function createTable(string $prefix , DModuleTable $table ) : bool
    {
        $fields = $table -> getFieldsList();
        $table_name = $prefix . $table->getName();
        /**
         * Now we are going to create the table in the database depending on the table information
         */

        $DModuleTable = $table;
        $action = "table";
        if( !\Schema::hasTable( $table_name )){
            $action = "create";
        }

        Schema::$action($table_name, function(Blueprint $table) use ($fields , $table_name , $DModuleTable , $prefix)
            {
                $table -> engine = config('database.default_engine');
                foreach($fields AS $f){
                    $auxExecution = "";
                    switch ( $f->type ){
                        case "bigIncrements":
                            $auxExecution .= '$table->bigIncrements($f->name , (int)$f->length ) -> unsigned()  ';
                        break;
                        case "increments":
                            $auxExecution .= ' $table->increments($f->name , (int)$f->length ) -> unsigned() ';
                        break;
                        case "string":
                            $auxExecution .= ' $table->string($f->name , (int)$f->length ) ';
                        break;
                        case "text":
                            $auxExecution .= ' $table->text($f->name , (int)$f->length ) ';
                        break;
                        case "longText":
                            $auxExecution .= ' $table->longText($f->name , (int)$f->length ) ';
                        break;
                        case "float":
                            $auxExecution .= ' $table->float($f->name , (int)$f->length ) ';
                        break;
                        case "integer":
                            $auxExecution .= ' $table->integer($f->name) -> unsigned() ';
                        break;
                        case "bigInteger":
                            $auxExecution .= ' $table->bigInteger($f->name ) -> unsigned() ';
                        break;
                        case "tinyInteger":

                            $auxExecution .= ' $table->tinyInteger($f->name ) -> unsigned() ';
                        break;
                        case "date":
                            $auxExecution .= ' $table->date($f->name ,  ) ';
                        break;
                        case "dateTime":
                           $auxExecution .= ' $table->dateTime($f->name) ';
                        break;
                        case "boolean":
                           $auxExecution .= ' $table->boolean($f->name) ';
                        break;
                        case "drop":
                            $auxExecution .= ' $table->dropColumn($f->name) ';
                        break;
                    }


                    if( !$f->required && $f->type != "drop" ){
                                    $auxExecution .= ' ->nullable() ';
                    }

                    if( \Schema::hasColumn( $table_name , $f->name ) ) {
                        $auxExecution .= ' ->change() ';
                    }
                    //dd( $auxExecution );
                    eval( $auxExecution . ';' );

                }

            });

        return true;
    }

    /**
     * This method should be called apart from the normal installation so we can be sure that the tables are already
     * created at this point.
     *
     * Este método debería ser llamado aparte del prcceso normal de instalación, para así estar seguros de que las tablas
     * ya están creadas en este punto.
     *
     * @param string $prefix
     * @param DModuleTable $table
     * @return bool
     */
    public static function manageIndexAndForeign( string $prefix , DModuleTable $table ) : bool
    {
        $fields = $table -> getFieldsList();
        $table_name = $prefix . $table->getName();
        if(\Schema::hasTable( $table_name )){
            $DModuleTable = $table;

            Schema::table($table_name, function(Blueprint $table) use ($fields , $table_name , $DModuleTable , $prefix)
            {
                /**
                 * We bring the schema builder
                 */
                 $schema_builder = \Schema::getConnection()->getDoctrineSchemaManager()->listTableDetails( $table->getTable() );

                /**
                 * If there are any index to create, we check if there is already an index with that name,
                 * and if not, then we create it.
                 */
                    foreach( $DModuleTable->getIndex() AS $key => $index ){
                        if( !$schema_builder->hasIndex( $index["name"] ) ){
                            $table->index( $index["column"] , $index["name"] );
                        }
                    }
                /**
                 * On the other side, we check if there is any index to drop, and if there is any and it exists,
                 * then we are going to drop it.
                 */

                    foreach( $DModuleTable->getIndexDrop() AS $key => $index ){
                        if( $schema_builder->hasIndex( $index ) ){
                            $table->dropIndex( $index );
                        }
                    }

                    /**
                     * Now lets work with the foreign keys.
                     */

                    foreach( $DModuleTable->getForeign() AS $key => $index ){
                        if( !$schema_builder->hasIndex( $index["name"] ) ){
                            $table_name_foreign = $index["table"];
                            if( (bool)$index["own"] == true ){
                                $table_name_foreign = $prefix . $table_name_foreign;
                            }
                            $table -> foreign( $index["columns"] , $index["name"] ) ->
                                    references( $index["foreign_column"] ) -> on ($table_name_foreign);
                        }
                    }

                    foreach( $DModuleTable->getForeignDrop() AS $key => $index ){
                        if( $schema_builder->hasIndex( $index ) ){
                            $table -> dropForeign( $index );
                        }
                    }
            });

            /*************************/
            /* DROPPING PRIMARY KEYS */
            /*************************/
            try{
            if( count($DModuleTable->getPrimaryKeysToDrop())>0 ) {
                Schema::table($table_name, function (Blueprint $table) use ($fields, $table_name, $DModuleTable, $prefix) {
                    /**
                     * Lets add the primary keys
                     */
                    $table->dropPrimary($DModuleTable->getPrimaryKeysToDrop());
                });
            }
            }catch( \Illuminate\Database\QueryException $e ){
                //throw new SystemException("Could not add the primary keys to the table: " . $table_name);
            }

            /***********************/
            /* ADDING PRIMARY KEYS */
            /***********************/

            try{
            if( count($DModuleTable->getPrimaryKeys())>0 ) {
                Schema::table($table_name, function (Blueprint $table) use ($fields, $table_name, $DModuleTable, $prefix) {
                    /**
                     * Lets add the primary keys
                     */
                    $table->primary($DModuleTable->getPrimaryKeys());
                });
            }
            }catch( \Illuminate\Database\QueryException $e ){
                //throw new SystemException("Could not add the primary keys to the table: " . $table_name);
            }


            /**************************/
            /* DROPPING UNIQUE FIELDS */
            /**************************/
            if( count($DModuleTable->getUniqueFieldsDrop()) > 0 ){
                        foreach( $DModuleTable->getUniqueFieldsDrop() AS $unique ){
                            try{
                             Schema::table($table_name, function(Blueprint $table) use ($fields , $table_name , $DModuleTable , $prefix , $unique) {
                                /**
                                 * Now the unique fields to drop.
                                 * We  are separating the unique fields into a new schema since we don't have a way to validate the
                                 * existence of unique fields. So we have to enclose this with a try catch statement
                                 *
                                 */

                                            /**
                                             * This try cath is in case that we already have the key defined
                                             */
                                            try{
                                                $table->dropUnique( $unique );
                                            }catch (\Exception $err){}catch(\Error $err){}

                            });
                            }catch( \Illuminate\Database\QueryException $e ){}
                        }
            }


            /************************/
            /* ADDING UNIQUE FIELDS */
            /************************/

            if( count($DModuleTable->getUniqueFields()) > 0 ){
                        foreach( $DModuleTable->getUniqueFields() AS $unique ){
                            try{
                             Schema::table($table_name, function(Blueprint $table) use ($fields , $table_name , $DModuleTable , $prefix , $unique) {
                                /**
                                 * Now the unique fields.
                                 * We  are separating the unique fields into a new schema since we don't have a way to validate the
                                 * existence of unique fields. So we have to enclose this with a try catch statement
                                 *
                                 */

                                            /**
                                             * This try cath is in case that we already have the key defined
                                             */
                                            try{
                                                $table->unique( $unique["field"] , $unique["name"] );
                                            }catch (\Exception $err){}catch(\Error $err){}

                            });
                            }catch( \Illuminate\Database\QueryException $e ){}
                        }
            }
        }
        return true;
    }


    /**
     * This method checks and deletes a table.
     *
     * Este método verifica y elimina una tabla.
     *
     * @param $prefix
     * @param DModuleTable $table
     */
    public static function deleteTable($prefix, \App\Classes\DModule\DModuleTable $table)
    {
        $fields = $table -> getFieldsList();
        $table_name = $prefix . $table->getName();
        Schema::dropIfExists( $table_name );
    }
}