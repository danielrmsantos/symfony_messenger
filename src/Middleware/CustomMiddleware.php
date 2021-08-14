<?php

namespace App\Middleware;

use App\Stamps\CustomStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class CustomMiddleware implements MiddlewareInterface
{

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $envelope->last(CustomStamp::class)) {
            $envelope = $envelope->with(new CustomStamp());
        }
        /** @var CustomStamp $stamp */
        $stamp = $envelope->last(CustomStamp::class);
        //dump($stamp->getUniqueId());
        print_r($stamp->getUniqueId());
        print_r($envelope->getMessage());
        echo "\n";
        return $stack->next()->handle($envelope, $stack);
    }
}