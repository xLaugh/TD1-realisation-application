<?php

namespace Tests\Entity;

use App\Entity\Person;
use App\Entity\Product;
use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testConstructor(): void
    {
        $person = new Person('John Doe', 'USD');
        $this->assertEquals('John Doe', $person->getName());
        $this->assertInstanceOf(Wallet::class, $person->getWallet());
        $this->assertEquals('USD', $person->getWallet()->getCurrency());
        $this->assertEquals(0, $person->getWallet()->getBalance());
    }

    public function testSetName(): void
    {
        $person = new Person('John', 'USD');
        $person->setName('Jane');
        $this->assertEquals('Jane', $person->getName());
    }

    public function testSetWallet(): void
    {
        $person = new Person('John', 'USD');
        $newWallet = new Wallet('EUR');
        $person->setWallet($newWallet);
        $this->assertSame($newWallet, $person->getWallet());
        $this->assertEquals('EUR', $person->getWallet()->getCurrency());
    }

    public function testHasFund(): void
    {
        $person = new Person('John', 'USD');
        $this->assertFalse($person->hasFund());

        $person->getWallet()->setBalance(10.0);
        $this->assertTrue($person->hasFund());
    }

    public function testTransfertFundSuccess(): void
    {
        $alice = new Person('Alice', 'USD');
        $alice->getWallet()->setBalance(100.0);
        
        $bob = new Person('Bob', 'USD');
        // Bob commence avec 0

        $alice->transfertFund(30.0, $bob);

        $this->assertEquals(70.0, $alice->getWallet()->getBalance());
        $this->assertEquals(30.0, $bob->getWallet()->getBalance());
    }

    public function testTransfertFundDifferentCurrency(): void
    {
        $alice = new Person('Alice', 'USD');
        $bob = new Person('Bob', 'EUR');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can\'t give money with different currencies');

        $alice->transfertFund(10.0, $bob);
    }

    public function testDivideWallet(): void
    {
        $alice = new Person('Alice', 'USD');
        $alice->getWallet()->setBalance(100.0);

        $bob = new Person('Bob', 'USD');
        $charlie = new Person('Charlie', 'USD');
        $dave = new Person('Dave', 'EUR'); // Doit être ignoré (mauvaise devise)

        // Division du portefeuille d'Alice (100 USD) entre Bob et Charlie.
        // Dave est ignoré.
        
        $persons = [$bob, $charlie, $dave];
        
        $alice->divideWallet($persons);

        // Alice donne tout.
        // 100 / 2 = 50 chacun.
        
        $this->assertEquals(0.0, $alice->getWallet()->getBalance());
        $this->assertEquals(50.0, $bob->getWallet()->getBalance());
        $this->assertEquals(50.0, $charlie->getWallet()->getBalance());
        $this->assertEquals(0.0, $dave->getWallet()->getBalance());
    }
    
    public function testDivideWalletUneven(): void
    {
        $alice = new Person('Alice', 'USD');
        $alice->getWallet()->setBalance(10.0);

        $p1 = new Person('P1', 'USD');
        $p2 = new Person('P2', 'USD');
        $p3 = new Person('P3', 'USD');
        
        // 10 / 3 = 3.33 (arrondi). Reste 0.01.
        // Le premier (P1) reçoit le reste : 3.33 + 0.01 = 3.34.
        
        $alice->divideWallet([$p1, $p2, $p3]);
        
        $this->assertEquals(0.0, $alice->getWallet()->getBalance(), 'Alice devrait être vide');
        $this->assertEquals(3.34, $p1->getWallet()->getBalance(), 'Solde P1');
        $this->assertEquals(3.33, $p2->getWallet()->getBalance(), 'Solde P2');
        $this->assertEquals(3.33, $p3->getWallet()->getBalance(), 'Solde P3');
    }

    public function testBuyProductSuccess(): void
    {
        $person = new Person('Buyer', 'USD');
        $person->getWallet()->setBalance(50.0);
        
        $product = new Product('Gadget', ['USD' => 20.0, 'EUR' => 18.0], 'tech');
        
        $person->buyProduct($product);
        
        $this->assertEquals(30.0, $person->getWallet()->getBalance());
    }

    public function testBuyProductInvalidCurrency(): void
    {
        $person = new Person('Buyer', 'USD');
        $person->getWallet()->setBalance(50.0);
        
        $product = new Product('Wine', ['EUR' => 10.0], 'alcohol');
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can\'t buy product with this wallet currency');
        
        $person->buyProduct($product);
    }
}
