<?php

namespace App\Console\Commands;


use App\Classes\Email\Email;
use App\Classes\ExtendedCommands;



class SendEmails extends ExtendedCommands
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendEmails';

    /**
     * This is the name of the artisan command. It is required by the ExtendedCommands class
     * @var string
     */
    protected $name = 'Send Emails';

    /**
     * The amount of emails we are trying to send in this process call.
     * @var int
     */
    private $max_emails_send = 5;


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will grab all waiting emails and send them. It should take groups of 5 depending on how much it takes sending the emails';

    public function __construct()
    {
        parent::__construct();
    }

	public function handle(){
		$this->header();
		$this->main();
		$this->endCommand();
	}

	public function main(){
        $this -> l("Welcome to the email send system.");
        $this -> l("We are sending  emails in this process, but one by one just in case the process stuck for one sending and it stars over.");

        /**
         * First we grab the oldest email that is wiating to be send, and lock it up
         */

        for( $i = 0 ; $i < $this -> max_emails_send ; $i++ ){
            $email = Email::where("status" , Email::STATUS_WAITING)->orderBy("created_at", "asc")->first();
            if( $email != null ){
                /**
                 * We re commenting this line for now
                 */
                $email -> changeStatus( Email::STATUS_PROCESSING ); // Lock it up
                $this -> cleanLine();
                $this -> l("===== ===== ===== ===== =====");
                $this -> l("Email found.");
                $this -> l("Subject: " . $email->subject );

                $recipients = $email->getRecipients();
                foreach( $recipients AS $recipient ){
                    $this -> l(" * Recipient: " . $recipient->getName() . '<'.$recipient->getEmail() . '>' );
                }

                try{
                    //We try to send the email
                   $email -> send();
                }catch (\Error $err){
                    $r = "An error occurred: " . $err->getMessage();
                    $this -> er( $r );
                }

                $this -> l("===== ===== ===== ===== =====");
                $this -> cleanLine();
            }
        }
	}
}
