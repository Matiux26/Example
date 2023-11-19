<?php

namespace app\Code\Example\Repositories;

use app\Code\Example\ActiveRecords\Contract;

class ContractsRepository
{
    public function getByNumber(int $id): Contract
    {
        return new Contract();
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