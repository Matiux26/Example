<?php

namespace app\Code\Example\DomainObject;

use DateTime;
use app\Code\Example\Money;

class RevenueRecognition
{
    public function __construct(
        public readonly Money $amount,
        public readonly DateTime $date,
    ) {
    }

    public function isRecognizableBy(DateTime $asOf): bool
    {
        return $asOf >= $this->date;
    }
}