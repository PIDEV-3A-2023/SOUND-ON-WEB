
<?php
//namespace App\Controller;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController
{
    
    public function loginSuccess(TexterInterface $texter)
    {
        $sms = new SmsMessage(
            // the phone number to send the SMS message to
            '+21620300934',
            // the message
            'A new login was detected!',
            // optionally, you can override default "from" defined in transports
            '+20300934',
        );

        $sentMessage = $texter->send($sms);

        // ...
    }
}