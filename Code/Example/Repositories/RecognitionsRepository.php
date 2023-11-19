<?php

namespace app\Code\Example\Repositories;

use DateTime;
use app\Code\Example\Money;
use app\Code\Example\ActiveRecords\Recognition;

class RecognitionsRepository
{
    public function findRecognitionsFor(int $contractNumber, DateTime $asOf)
    {
        return new Recognition();
    }

    public function find(...$args): array
    {
        return [];
    }

    public function insert(...$args): array
    {
        return [];
    }

    public function update(...$args): array
    {
        return [];
    }

    public function delete(...$args): array
    {
        return [];
    }
}