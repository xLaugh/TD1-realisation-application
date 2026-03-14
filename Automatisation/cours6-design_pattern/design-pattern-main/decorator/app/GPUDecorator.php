<?php

namespace App;

class GPUDecorator extends ComputerDecorator {
    
    public function getPrice(): int
    {
        return $this->computer->getPrice() + 450;
    }

    public function getDescription(): string
    {
        return $this->computer->getDescription() . " with a GPU";
    }
}   