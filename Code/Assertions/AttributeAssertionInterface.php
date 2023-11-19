<?php

namespace app\Code\Assertions;

interface AttributeAssertionInterface
{
    public function check(mixed $value, string $propertyName): bool;
}