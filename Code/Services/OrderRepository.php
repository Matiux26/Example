<?php

namespace app\Code\Services;

class OrderRepository
{
    public function loadOrder(int $orderId): Order 
    {
        return new Order();
    }

    public function getVersion(int $orderId): int 
    {
       $order = $this->loadOrder($orderId);

        return $order->version;
    }

    public function saveOrder(Order $order): void {}
}