<?php

namespace app\Code\Example\DomainObject\Strategies;

use app\Code\Example\Money;
use app\Code\Example\DomainObject\Contract;
use app\Code\Example\DomainObject\RevenueRecognition;
use app\Code\Example\DomainObject\RecognitionStrategyInterface;

class ThreeWayRecognitionStrategy implements RecognitionStrategyInterface
{
    public function __construct(
        private int $firstRecognitionOffset,
        private int $secondRecognitionOffset,
    ) {
    }

    public function calculateRevenueRecognitions(Contract $contract): void
    {
        /** 
         * We can't split amount by just simple division
         * Because we would have 0.33, 0.33, 0.33 = 0.99 and we would have lost a penny
         * That's why we use allocate which will result in 0.33, 0.33, 0.34 = 1.00
        */
        /** @var Money[] $allocation */
        $allocation = $contract->revenue->allocate(3);

        $contract->addRevenueRecognition(
            new RevenueRecognition(
                $allocation[0],
                $contract->whenSigned
            )
        );

        $contract->addRevenueRecognition(
            new RevenueRecognition(
                $allocation[1],
                $contract->whenSigned->modify("+ $this->firstRecognitionOffset day")
            )
        );

        $contract->addRevenueRecognition(
            new RevenueRecognition(
                $allocation[2],
                $contract->whenSigned->modify("+ $this->secondRecognitionOffset day")
            )
        );
    }
}
