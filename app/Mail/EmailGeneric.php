<?php

namespace App\Mail;

use App\Classes\Common;
use App\Classes\Email\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailGeneric extends Mailable
{

    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $template = 'basic';

    /**
     * The email registry we are using
     * @var \App\Classes\Email\Email
     */
    public $EmailRegistry = null;



    /**
     * Create a new message instance.
     *
     * @param Email $email
     * @param string $template
     */
    public function __construct(Email $email , string $template = null)
    {
        $this->EmailRegistry = $email;
        if( $template != null ){
            $this -> template = $template;
        }
    }


    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }



    public function build()
    {
        $this -> subject( $this -> EmailRegistry -> subject );
        $attachments = $this -> EmailRegistry ->  getAttachments();
        foreach( $attachments AS $attachment ){

            $this->attach( $attachment->fileObject -> getRealLocation() , [
                'as' => $this -> clean_file_name($attachment->name) . '.' . $attachment->fileObject->getExtension() ,
                'mime' => $attachment->fileObject->getMime(),
            ]);
        }

        return $this->view( 'email.' . $this->template );
    }


    private function clean_file_name(string $filename) : string
    {
        return strtolower( Common::remove_accents( Common::transliterateString( Common::clean( $filename ) ) ) );

    }
}
