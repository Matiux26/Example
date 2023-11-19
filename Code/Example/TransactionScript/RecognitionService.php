<?php

namespace app\Code\Example\TransactionScript;

use DateTime;
use app\Code\Example\Money;
use app\Code\Example\Repositories\ContractsRepository;
use app\Code\Example\Repositories\RecognitionsRepository;

class RecognitionService
{
    public function __construct(
        private RecognitionsRepository $recognitionsRepository,
        private ContractsRepository $contractsRepository,
    ) {
    }

    public function recognizedRevenue(int $contractNumber, DateTime $asOf): Money
    {
        $result = Money::dollars(0);

        $recognitions = $this->recognitionsRepository->findRecognitionsFor($contractNumber, $asOf);

        foreach($recognitions as $recognition) {
            $result->add(Money::dollars($recognition->amount));
        }

        return $result;
    }

    public function calculateRevenueRecognitions(int $contractNumber): void
    {
        $contract = $this->contractsRepository->getByNumber($contractNumber);
        $totalRevenue = Money::dollars($contract->revenue);
        $recognitionDate = new DateTime($contract->dateSigned);
        $contractType = $contract->type;

        if($contractType === 'S') {
            $this->recognitionsRepository->insert($contractNumber, $totalRevenue, $recognitionDate);
            $this->recognitionsRepository->insert($contractNumber, $totalRevenue, $recognitionDate->modify('30 days'));
            $this->recognitionsRepository->insert($contractNumber, $totalRevenue, $recognitionDate->modify('90 days'));
        } elseif ($contractType === 'W') {
            $this->recognitionsRepository->insert($contractNumber, $totalRevenue, $recognitionDate);
        } elseif($contractType === 'D') {
            $this->recognitionsRepository->insert($contractNumber, $totalRevenue, $recognitionDate);
            $this->recognitionsRepository->insert($contractNumber, $totalRevenue, $recognitionDate->modify('30 days'));
            $this->recognitionsRepository->insert($contractNumber, $totalRevenue, $recognitionDate->modify('60 days'));
        }

    }
}