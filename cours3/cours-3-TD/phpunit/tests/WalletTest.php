<?php

namespace Tests;

use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    public function testWalletBalance(): void //teste la balance initiale du wallet
    {
        $wallet = new Wallet('USD');
        $this->assertEquals(0, $wallet->getBalance());
    }

    public function testWalletAddFund(): void //teste l'ajout de fonds au wallet
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $this->assertEquals(100, $wallet->getBalance());
    }

    public function testWalletRemoveFund(): void //teste le retrait de fonds au wallet
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $wallet->removeFund(50);
        $this->assertEquals(50, $wallet->getBalance());
    }
    public function testWalletRemoveFundWithNegativeAmount(): void //teste le retrait de fonds avec un montant négatif
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid amount');
        $wallet->removeFund(-10);
    }
    public function testWalletRemoveFundWithInsufficientFunds(): void //teste le retrait de fonds avec un montant supérieur au solde
    {
        $wallet = new Wallet('EUR');
        $wallet->addFund(50);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient funds');
        $wallet->removeFund(100);
    }
    public function testWalletRemoveFundFromEmptyWallet(): void //teste le retrait de fonds avec un solde nul
    {
        $wallet = new Wallet('EUR');
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient funds');
        $wallet->removeFund(10);
    }
    public function testWalletAddFundWithNegativeAmount(): void //teste l'ajout de fonds avec un montant négatif
    {
        $wallet = new Wallet('EUR');
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid amount');
        $wallet->addFund(-10);
    }
    public function testWalletSetBalanceWithNegativeAmount(): void //teste le set de balance avec un montant négatif
    {
        $wallet = new Wallet('EUR');
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid balance');
        $wallet->setBalance(-10);
    }
    public function testWalletSetBalanceWithValidAmount(): void //teste le set de balance avec un montant valide
    {
        $wallet = new Wallet('EUR');
        $wallet->setBalance(100);
        $this->assertEquals(100, $wallet->getBalance());
    }
    public function testWalletSetCurrencyWithValidCurrency(): void //teste le set de devise avec une devise valide
    {
        $wallet = new Wallet('EUR');
        $wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $wallet->getCurrency());
    }
    public function testWalletSetCurrencyWithInvalidCurrency(): void //teste le set de devise avec une devise invalide
    {
        $wallet = new Wallet('EUR');
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid currency');
        $wallet->setCurrency('GBP');
    }
    public function testWalletGetCurrency(): void //teste la récupération de la devise
    {
        $wallet = new Wallet('EUR');
        $this->assertEquals('EUR', $wallet->getCurrency());
    }
    public function testWalletGetBalance(): void //teste la récupération du solde
    {
        $wallet = new Wallet('EUR');
        $this->assertEquals(0, $wallet->getBalance());
    }
}