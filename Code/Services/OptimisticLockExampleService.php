<?php

namespace app\Code\Services;

use yii\base\Request;
use yii\db\Connection;

class OptimisticLockExampleService
{
    public function __construct(
        private OrderLockService $orderLockService,
        private OrderRepository $orderRepository,
        private OrderService $orderService,
        private Connection $connection,
    ) {
    }

    public function performBusinessLogic(int $id, Request $request): void
    {
        $order = $this->orderRepository->loadOrder($id);
        
        $this->orderService->update($order, $request);

        $this->connection->transaction(function () use ($order) {
            $this->orderLockService->acquireLock($order);
            $this->orderRepository->saveOrder($order);
        });
    }
}