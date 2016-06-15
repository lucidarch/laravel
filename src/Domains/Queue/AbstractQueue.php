<?php

namespace App\Domains\Queue;

abstract class AbstractQueue
{
    protected $name = '';

    public function __toString()
    {
        return $this->name;
    }
}
