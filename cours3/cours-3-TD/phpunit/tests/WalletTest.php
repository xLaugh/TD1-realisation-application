<?php

namespace Tests;

use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    public function testWalletBalance(): void
    {
        $wallet = new Wallet('USD');
        $this->assertEquals(0, $wallet->getBalance());
    }

    public function testWalletAddFund(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $this->assertEquals(100, $wallet->getBalance());
    }

    public function testWalletRemoveFund(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $wallet->removeFund(50);
        $this->assertEquals(50, $wallet->getBalance());
    }
    public function testWalletRemoveFundWithNegativeAmount(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $wallet->removeFund(-10);
    }
    public function testWalletRemoveFundWithInsufficientFunds(): void 
    {
        $wallet = new Wallet('EUR');
        $wallet->addFund(50);
        $wallet->removeFund(100);
    }
    public function testWalletRemoveFundFromEmptyWallet(): void    
    {
        $wallet = new Wallet('EUR');
        $wallet->removeFund(10);
    }
    public function testWalletAddFundWithNegativeAmount(): void
    {
        $wallet = new Wallet('EUR');
        $wallet->addFund(-10);
    }
    public function testWalletSetBalanceWithNegativeAmount(): void
    {
        $wallet = new Wallet('EUR');
        $wallet->setBalance(-10);
    }
    public function testWalletSetBalanceWithValidAmount(): void
    {
        $wallet = new Wallet('EUR');
        $wallet->setBalance(100);
        $this->assertEquals(100, $wallet->getBalance());
    }
    public function testWalletSetCurrencyWithInvalidCurrency(): void
    {
        $wallet = new Wallet('EUR');
        $wallet->setCurrency('USD');
    }
    public function testWalletSetCurrencyWithValidCurrency(): void
    {
        $wallet = new Wallet('EUR');
        $wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $wallet->getCurrency());
    }
}