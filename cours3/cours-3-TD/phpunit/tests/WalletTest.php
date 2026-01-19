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

    public function testWalletConstructorWithInvalidCurrency(): void //teste le constructeur avec une devise invalide
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid currency');
        new Wallet('GBP');
    }

    public function testWalletAddFundWithZeroAmount(): void //teste l'ajout de fonds avec un montant zéro
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(0);
        $this->assertEquals(0, $wallet->getBalance());
    }

    public function testWalletRemoveFundWithExactBalance(): void //teste le retrait de fonds avec un montant égal au solde
    {
        $wallet = new Wallet('EUR');
        $wallet->addFund(100);
        $wallet->removeFund(100);
        $this->assertEquals(0, $wallet->getBalance());
    }

    public function testWalletMultipleOperations(): void //teste plusieurs opérations successives (addFund et removeFund)
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $wallet->addFund(50);
        $wallet->removeFund(30);
        $wallet->addFund(25);
        $wallet->removeFund(45);
        $this->assertEquals(100, $wallet->getBalance());
    }

    public function testWalletSetBalanceWithZeroAmount(): void //teste le set de balance avec un montant zéro
    {
        $wallet = new Wallet('USD');
        $wallet->setBalance(0);
        $this->assertEquals(0, $wallet->getBalance());
    }
}