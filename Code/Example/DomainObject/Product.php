<?php

namespace app\Code\Example\DomainObject;

use app\Code\Example\DomainObject\Strategies\CompleteRecognitionStrategy;

class Product
{
    public function __construct(
        private string $name,
        private RecognitionStrategyInterface $recognitionStrategy
    ) {
    }

    public function newWordProcessor(string $name): self
    {
        return new self($name, new CompleteRecognitionStrategy());
    }

    public function newSpreadSheet(string $name): self
    {
        return new self($name, new COmpleteRecognitionStrategy());
    }

    public function newDatabase(string $name): self
    {
        return new self($name, new CompleteRecognitionStrategy());
    }

    public function calculateRevenueRecognitions(Contract $contract): void
    {
        $this->recognitionStrategy->calculateRevenueRecognitions($contract);
    }

    public static function fromArray(array $productData): self
    {
        return match ($productData['type']) {
            'word_processor' => self::newWordProcessor($productData['name']),
            'spreadsheet' => self::newSpreadSheet($productData['name']),
            'database' => self::newDatabase($productData['name'])
        };
    }
}