<?php

namespace app\Code\Example\DomainObject;

use DateTime;
use app\Code\Example\Money;

class Contract
{
    /** @var RevenueRecognition[] $revenueRecognitions */
    private array $revenueRecognitions;

    public function __construct(
        public readonly Product $product,
        public readonly Money $revenue,
        public readonly DateTime $whenSigned,
    ) {
    }

    public function addRevenueRecognition(RevenueRecognition $revenueRecognition): void
    {
        $this->revenueRecognitions[] = $revenueRecognition;
    }

    public function recognizedRevenue(DateTime $asOf): Money
    {
        $result = Money::dollars(0);

        foreach ($this->revenueRecognitions as $revenueRecognition) {
            if ($revenueRecognition->isRecognizableBy($asOf)) {
                $result->add($revenueRecognition->amount);
            }
        }

        return $result;
    }

    public function calculateRecognitions(): void
    {
        $this->product->calculateRevenueRecognitions($this);
    }

    public static function fromArray(array $contractData): self
    {
        return new self(
            $contractData['product'],
            $contractData['revenue'],
            new DateTime($contractData['dateSigned']),
        );
    }
}