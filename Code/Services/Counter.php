<?php

namespace app\Code\Services;

class Counter implements IdGenerator
{
    private int $count;

    public function nextId(): int
    {
        $this->count++;
        
        return $this->count;
    }
}