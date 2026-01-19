<?php

namespace Tests;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private Product $produit;
    private Product $produit2;

    public function setUp(): void {
        $this->produit = new Product("Test",["EUR" => 10.0],"other");
        $this->produit2 = new Product("Burger",["EUR" => 13.0],"food");
    }


    //Test constructeur
    public function testConstructName(): void
    {
        $this->assertEquals($this->produit->getName(),"Test");
    }

    public function testConstructPrice(): void
    {
        $this->assertEquals($this->produit->getPrices(),["EUR" => 10.0]);
    }

    public function testConstructType(): void
    {
        $this->assertEquals($this->produit->getType(),"other");
    }


    //Test insertion de type
    public function testTypeCorrect(): void
    {
        $this->produit->setType("tech");
        $this->assertEquals($this->produit->getType(), "tech");
    }

    public function testTypeMauvais(): void
    {
        $this->expectException(\Exception::class);
        $this->produit->setType("Voiture");
    }

    //Test insertion prix
    public function testPrixCorrect(): void
    {
        $this->produit->setPrices(["EUR" => 12.0]);
        $this->assertEquals($this->produit->getPrice('EUR'), 12.0);
    }

    public function testPrixMonnaieInexistante(): void
    {
        $this->produit->setPrices(["CAD" => 13.0]);
        $this->assertEquals($this->produit->getPrices(), ["EUR" => 10.0]); //Il existe seulement EUR car il est ajouté à l'initilisation
    }

    public function testPrixNegatif(): void
    {
        $this->produit->setPrices(["EUR" => -1.0]);
        $this->assertEquals($this->produit->getPrice('EUR'), 10.0); //Prix inchangé
    }

    //Get TVA
    public function testTVAOther(): void
    {
        $this->assertEquals($this->produit->getTVA(), 0.2);
    }

    public function testTVAFood(): void
    {
        $this->assertEquals($this->produit2->getTVA(), 0.1);
    }

    //Get Price
    public function testPrice(): void
    {
        $this->expectException(\Exception::class);
        $prix = $this->produit->getPrice('CAD');
    }

    //List Currencies
    public function testListCurrencies(): void
    {
        $this->assertEquals($this->produit->listCurrencies(), ['EUR']);
    }
    
}