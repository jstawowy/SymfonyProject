<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class NotificationsController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications')]
    public function index(NotifierInterface $notifier):Response
    {
        $sms = new SmsMessage(
            '+48645346453', //to
            'A new login was detected!', //message
            '+48345234907', //from
        );

        //$sentTextMessage = $texter->send($sms);

        $slack = (new ChatMessage('You got a new invoice.'))
            ->transport('slack');

       // $sentChatMessage = $chatter->send($slack);

       $emailNotification = (new Notification('Invoice', ['email']))
       ->content('Watchout!');

        $recipient = new Recipient(
            "jakubst2000@wp.pl"
        );

        // Send the notification
       // $notifier->send($emailNotification, $recipient);

        return new Response("<h2>Notification system ready to use, just implement DSNs, credentials & uncomment</h2>");
    }
}
