<?php

namespace app\Code\Services;

interface Mapper
{
    public function find(int $id): Order;
    public function insert(Order $order): void;
    public function update(Order $order): void;
    public function delete(Order $order): void;
}