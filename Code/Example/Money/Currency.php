<?php

namespace app\Code\Example;

class Currency
{
    public const USD = 'USD';
    public const JPY = 'JPY';

    public function __construct(
        public readonly string $currency,
    ) {   
    }

    public static function USD(): self
    {
        return new self(self::USD);
    }

    public function getDefaultFractionDigits(): int
    {
        return match($this->currency) {
            self::USD => 2,
            self::JPY => 0,
        };
    }

    public function equals(Currency $currency): bool
    {
        return $this->currency === $currency->currency;
    }
}