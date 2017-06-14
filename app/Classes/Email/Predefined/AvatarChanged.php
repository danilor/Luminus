<?php


namespace App\Classes\Email\Predefined;

use App\User;
class AvatarChanged extends GenericEmail
{
    private $user = null;

    /**
     * SuspiciousAttempts constructor.
     * @param User $user
     * @param string $avatar
     * @param array $attachment_image
     */
    public function __construct( User $user , string $avatar, array $attachment_image = [] )
    {
        $this -> user =  $user;
        $this -> setBody ( "
                        <p>Su avatar ha sido modificado exitosamente. Adjunto encontrará la imágen que ha sido subida para su perfil.</p>
                        <p>
                        <center>
                            <img src='$avatar' width='80%' />
                        </center>
                        </p>
                    " );

        $this -> setSubject( 'Avatar Modificado' );

        $recipient = new \App\Classes\Email\EmailRecipient( $user -> email, $user -> getFullName() , \App\Classes\Email\EmailRecipient::TYPE_TO ); //We create a single recipient
        $this -> addRecipient( $recipient );

        foreach( $attachment_image AS $att ){
            $this -> addAttachment( $att , 'avatar'  );
        }

    }
}