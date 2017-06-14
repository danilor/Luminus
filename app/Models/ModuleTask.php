<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleTask extends Model
{


    public function updateLastExecution(){
        $this -> last_execution = new \DateTime();
    }

    /**
     * This method will calculate the next time for the execution of this task
     * @return \DateTime
     */
    public function getNextTimeOfExecution() : \DateTime
    {
        $dated = new \DateTime();
        if( $this -> type == "f" ){
            $dated -> modify( "+" . $this -> year . " years" );
            $dated -> modify( "+" . $this -> month . " months" );
            $dated -> modify( "+" . $this -> day . " days" );
            $dated -> modify( "+" . $this -> hour . " hours" );
            $dated -> modify( "+" . $this -> minute . " minute" );
        }elseif( $this -> type == "t" ){

            var_dump( ' Not avaiable ' );

        }else{
            /**
             * it should not arrive here, but just in case...
             */
        }
        $this -> next_execution     =       $dated;
        return $dated;
    }

    /**
     * This method will set up the progress for this module
     * @param int $progress
     */
    public function setProgress( int $progress){
        $this -> in_progress = $progress;
        $this -> save();
    }
}
