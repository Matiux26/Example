<?php

namespace app\Code\Attributes;

use Attribute;

#[Attribute]
class ArrayStructureMap
{
    public function __construct(public string $className)
    {
    }
}