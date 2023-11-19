<?php

namespace app\Code\Services;

use yii\base\Request;
use yii\db\Connection;

class PesimissticReadLockExampleService
{
    public function __construct(
        private ExclusiveReadOrderLock $orderLockService,
        private OrderRepository $orderRepository,
        private OrderService $orderService,
        private Connection $connection,
    ) {
    }

    public function performBusinessLogic(int $id, Request $request): void
    {
        $this->orderLockService->acquireLock($id);
        try {
            $this->modifyOrder($id, $request);
        } finally {
            $this->orderLockService->releaseLock($id);
        }
    }

    private function modifyOrder(int $id, Request $request): void
    {
        $order = $this->orderRepository->loadOrder($id);
        $this->orderService->update($order, $request);
        $this->orderRepository->saveOrder($order);
    }
}