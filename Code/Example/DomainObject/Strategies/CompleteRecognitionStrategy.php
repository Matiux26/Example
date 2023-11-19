<?php

namespace app\Code\Example\DomainObject\Strategies;

use app\Code\Example\DomainObject\Contract;
use app\Code\Example\DomainObject\RecognitionStrategyInterface;
use app\Code\Example\DomainObject\RevenueRecognition;

class CompleteRecognitionStrategy implements RecognitionStrategyInterface
{
    public function calculateRevenueRecognitions(Contract $contract): void
    {
        $contract->addRevenueRecognition(
            new RevenueRecognition(
                $contract->revenue,
                $contract->whenSigned
            )
        );
    }
}