<?php

namespace app\Code\Example;

use app\Code\Example\Currency;
use Exception;

class Money
{
    public const CENTS = [
        0 => 1,
        1 => 10,
        2 => 100,
        3 => 1000,
    ];

    public readonly float $amount;
    
    //========================== Creation

    public function __construct(
        float $amount,
        public readonly Currency $currency,
    ) {
        $this->amount = round($amount * self::CENTS[$currency->getDefaultFractionDigits()]);
    }

    public static function dollars(float $amount): self
    {
        return new self($amount, Currency::USD());
    }

    private function newMoney(float $amount): self
    {
        return new self($amount, $this->currency);
    }

    //========================== Comparisons

    public function equals(Money $other): bool
    {
        return $this->currency->equals($other->currency) && ($this->amount === $other->amount);
    }

    public function greaterThan(Money $other): int
    {
        return ($this->compareTo($other) > 0);
    }

    public function compareTo(Money $other): int
    {
        $this->assertSameCurrencyAs($other);
        
        if ($this->amount < $other->amount) {
            return -1;
        } elseif ($this->amount === $other->amount) {
            return 0;
        }

        return 1;
    }

    //========================== Operations
    
    public function add(Money $other): Money
    {
        $this->assertSameCurrencyAs($other);

        return $this->newMoney($this->amount + $other->amount);
    }

    public function subtract(Money $other): Money
    {
        $this->assertSameCurrencyAs($other);

        return $this->newMoney($this->amount - $other->amount);
    }

    private function assertSameCurrencyAs(Money $other): void
    {
        if (!$this->currency->equals($other->currency)) {
            throw new Exception('Currency mismatch');
        }
    }

    /**
     * @return Money[]
     */
    public function allocate(int $n): array
    {
        $lowResult = $this->newMoney($this->amount / $n);
        $highResult = $this->newMoney($lowResult->amount + 1);

        $remainder = (int) $this->amount % $n;

        for ($i = 0; $i < $remainder; $i++) {
            $results[$i] = $highResult;
        }

        for ($i = $remainder; $i < $n; $i++) {
            $results[$i] = $lowResult;
        }

        return $results;
    }
}