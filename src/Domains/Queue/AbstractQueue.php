<?php

namespace App\Domains\Queue;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
abstract class AbstractQueue
{
    protected $name = '';

    public function __toString()
    {
        return $this->name;
    }
}
