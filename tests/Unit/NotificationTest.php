<?php
use PHPUnit\Framework\TestCase;
use App\Entity\Product;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\lessThan;
use function PHPUnit\Framework\lessThanOrEqual;

class NotificationTest extends TestCase
{
    public function testSMS(): void
    {

        $sms = new SmsMessage(
            '+48645346453', //to
            'A new login was detected!', //message
            '+48345234907', //from
        );
        self::assertSame(expected:'+48645346453', actual: $sms->getPhone() );
        self::assertSame(expected:'A new login was detected!', actual: $sms->getSubject() );
        self::assertSame(expected:'+48345234907', actual: $sms->getFrom() );
        self::assertGreaterThan(expected: 9, actual: strlen($sms->getPhone()));
        self::assertLessThanOrEqual(expected:16, actual:strlen($sms->getPhone()));
    }
    public function testSlack():void{

        $slack = (new ChatMessage('You got a new invoice.'))
            ->transport('slack');

        self::assertSame(expected:'You got a new invoice.', actual: $slack->getSubject() );
        self::assertSame(expected:'slack', actual: $slack->getTransport() );
        
    }

    public function testEmail():void{
        $emailNotification = (new Notification('Invoice', ['email']))
       ->content('Watchout!');
       $recipient = new Recipient(
        "jakubst2000@wp.pl"
    );
       self::assertSame(expected:'Invoice', actual: $emailNotification->getSubject() );
       self::assertSame(expected:'Watchout!', actual: $emailNotification->getContent() );
       self::assertSame(expected: ['email'], actual: $emailNotification->getChannels($recipient) );
       self::assertSame(expected: "jakubst2000@wp.pl", actual: $recipient->getEmail() );
       self::assertStringContainsString("@", $recipient->getEmail());
    
    }
}