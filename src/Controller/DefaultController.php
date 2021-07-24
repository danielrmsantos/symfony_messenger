<?php


namespace App\Controller;


use App\Message\TestMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;

class DefaultController extends AbstractController
{
    public function index(MessageBusInterface $bus): Response
    {
        $number = random_int(0, 100);
    
        /*$email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
    
        $mailer->send($email);*/
    
        $this->dispatchMessage(new TestMessage('Look! I created a message!'));
        
        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}