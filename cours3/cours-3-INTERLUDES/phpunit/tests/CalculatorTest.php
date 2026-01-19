<?php

namespace Tests;

use App\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function setUp(): void {
        $this->calculator = new Calculator();
    }

    public function testAdd(): void {
        $res = $this->calculator->add(2,2);
        self::asserrtEquals(4,$res);
    }
}
