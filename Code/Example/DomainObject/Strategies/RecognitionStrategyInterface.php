<?php

namespace app\Code\Example\DomainObject;

interface RecognitionStrategyInterface
{
    public function calculateRevenueRecognitions(Contract $contract): void;
}