<?php

namespace app\Code\Services;

use Exception;
use yii\mutex\Mutex;
use yii\web\BadRequestHttpException;

class ExclusiveReadOrderLock
{
    public function __construct(private Mutex $mutex)
    {
    }

    public function acquireLock(int $id): void 
    {
        if (!$this->mutex->acquire('read_order_to_edit_' . $id)) {
            throw new BadRequestHttpException('Cannot edit the order');
        }
    }

    public function releaseLock(int $id): void 
    {
        if (!$this->mutex->release('read_order_to_edit_' . $id)) {
            throw new Exception('Lock not found or already released');
        }
    }
}