<?php


namespace App\Classes\Email\Predefined;

use App\User;
class PasswordChanged extends GenericEmail
{
    private $user = null;

    /**
     * PasswordChanged constructor.
     * @param User $user
     * @internal param string $avatar
     * @internal param array $attachment_image
     */
    public function __construct( User $user  )
    {
        $this -> user =  $user;
        $this -> setBody ( "
                        <p>La información de acceso relacionada con el usuario <strong>" . $user->username . " (" . $user->email . ")</strong> ha sido modificada. Si usted no ha realizado este cambio, por favor contacte al administrador del sitio lo antes posible.</p>
                    " );
        $this -> setSubject( 'Información de acceso modificada' );

        $recipient = new \App\Classes\Email\EmailRecipient( $user -> email, $user -> getFullName() , \App\Classes\Email\EmailRecipient::TYPE_TO ); //We create a single recipient
        $this -> addRecipient( $recipient );
    }
}