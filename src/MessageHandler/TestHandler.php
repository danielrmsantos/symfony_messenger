<?php


namespace App\MessageHandler;


use App\Message\TestMessage;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;

class TestHandler implements MessageHandlerInterface
{
    private $mailer;
    
    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }
    
    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(TestMessage $message)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html($message->getContent());
    
        $this->mailer->send($email);
    }
}