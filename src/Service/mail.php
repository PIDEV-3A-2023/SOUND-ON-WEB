<?php
namespace App\Service;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class mail
{
    
    private $mailer;
    
    
    public function __construct( MailerInterface $mailer)
     {
        
        $this->mailer=$mailer;
     }
    
    public function sendEmail(    $post,$to): void
    {
        
        $email = (new Email())
            ->from('marwen.mamlouk@esprit.tn')
            ->to($to)
            ->subject('Validation de Commande')
            ->text($post);
             
            $this->mailer->send($email);
      
        // ...
    }
}
?>