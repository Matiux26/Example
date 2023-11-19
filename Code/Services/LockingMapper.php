<?php

namespace app\Code\Services;

class LockingMapper implements Mapper
{
    public function __construct(
        private Mapper $concreteMapper,
        private ExclusiveReadOrderLock $orderLockService
    ) {
    }

    public function find(int $id): Order
    {
        $this->orderLockService->acquireLock($id);
        
        return $this->concreteMapper->find($id);
    }

    public function insert(Order $order): void 
    {
        $this->concreteMapper->insert($order);
    }

    public function update(Order $order): void 
    {
        $this->concreteMapper->update($order);
    }

    public function delete(Order $order): void 
    {
        $this->concreteMapper->delete($order);
    }
}