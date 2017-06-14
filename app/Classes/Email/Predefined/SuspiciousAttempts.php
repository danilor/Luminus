<?php


namespace App\Classes\Email\Predefined;

use App\User;
class SuspiciousAttempts extends GenericEmail
{
    private $user = null;
    /**
     * SuspiciousAttempts constructor.
     * @param User $user
     * @param array $ips
     */
    public function __construct( User $user , array $ips )
    {
        $this -> user =  $user;
        $this -> setBody ( "
                        <p>Se han registrado múltiples intentos de ingreso con el usuario <strong>" . $user -> username . "</strong> al sistema durante la pasada hora. Esto se ha tomado como una acción sospechosa y por ello se está enviando este correo.</p>
                        <p>Si los intentos fueron realizados por el dueño de esta cuenta, por favor omitir este mensaje. Si considera que su cuenta ha sido comprometida, le recomendamos el cambiar su contraseña inmediatamente y contactarse con los administradores del sitio.</p>
                        <p>Los intentos se han registrado desde las siguientes IPs:</p>
                        <p><strong>" . implode( "," ,$ips ) . "</strong></p>
                    " );

        $this -> setSubject( 'Múltiples intentos de ingreso fallidos' );

        $recipient = new \App\Classes\Email\EmailRecipient( $user -> email, $user -> getFullName() , \App\Classes\Email\EmailRecipient::TYPE_TO ); //We create a single recipient
        $this -> addRecipient( $recipient );
    }
}