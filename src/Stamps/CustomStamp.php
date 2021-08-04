<?php

namespace App\Stamps;

use Symfony\Component\Messenger\Stamp\StampInterface;

class CustomStamp implements StampInterface
{
    private string $uniqueId;
    public function __construct()
    {
        $this->uniqueId = uniqid();
    }
    public function getUniqueId(): string
    {
        return $this->uniqueId;
    }
}