<?php

namespace App;

abstract class ComputerDecorator implements Computer
{
    protected $computer;

    public function __construct(Computer $computer)
    {
        $this->computer = $computer;
    }
}