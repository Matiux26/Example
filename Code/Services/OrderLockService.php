<?php

namespace app\Code\Services;

use yii\web\BadRequestHttpException;

class OrderLockService
{
    public function __construct(private OrderRepository $orderRepository)
    {
    }

    /**
     * @throws BadRequestHttpException
     */
    public function acquireLock(Order $order): void 
    {
        $orderVersion = $this->orderRepository->getVersion($order->id);

        if ($orderVersion !== $order->version) {
            throw new BadRequestHttpException('Attempted to update old version of order');
        }
    }
}