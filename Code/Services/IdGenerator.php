<?php

namespace app\Code\Services;

interface IdGenerator
{
    public function nextId(): int;
}