<?php

namespace app\Code\Example\Mapper;

use DateTime;
use InvalidArgumentException;
use app\Code\Example\DomainObject\Product;
use app\Code\Example\DomainObject\Contract;
use app\Code\Example\Repositories\ProductsRepository;
use app\Code\Example\Repositories\ContractsRepository;

class ContractMapper
{
    public function __construct(
        private ContractsRepository $contractsRepository,
        private ProductsRepository $productsRepository,
    ) {
    }

    public function find(int $id): Contract
    {
        $result = $this->contractsRepository->find($id);

        if ($result === null) {
            throw new InvalidArgumentException("Contract not found");
        }

        $product = $this->productsRepository->find($result['product_id']);
        $result['product'] = Product::fromArray($product);

        return Contract::fromArray($result);
    }

    public function insert(
        int $productId,
        float $revenue,
        DateTime $dateSigned,
    ): Contract {
        $result = $this->contractsRepository->insert($productId, $revenue, $dateSigned);

        $product = $this->productsRepository->find($result['product_id']);
        $result['product'] = Product::fromArray($product);

        return Contract::fromArray($result);
    }

    public function update(
        int $productId,
        float $revenue,
        DateTime $dateSigned,
    ): Contract {
        $result = $this->contractsRepository->update($productId, $revenue, $dateSigned);

        $product = $this->productsRepository->find($result['product_id']);
        $result['product'] = Product::fromArray($product);

        return Contract::fromArray($result);
    }

    public function delete(int $id): void
    {
        $this->contractsRepository->delete($id);
    }
}
