<?php

namespace App;

class OLEDScreenDecorator extends ComputerDecorator {
    
    public function getPrice(): int
    {
        return $this->computer->getPrice() + 150;
    }

    public function getDescription(): string
    {
        return $this->computer->getDescription() . " with a OLED Screen";
    }
}   