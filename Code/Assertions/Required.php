<?php

namespace app\Code\Assertions;

use Attribute;
use yii\web\BadRequestHttpException;
use app\Code\Assertions\AttributeAssertionInterface;

#[Attribute]
class Required implements AttributeAssertionInterface
{
    public function __construct()
    {
    }

    public function check(mixed $value, string $propertyName): bool
    {
        if ($value === null) {
            throw new BadRequestHttpException("Property: $propertyName cannot be null");
        }

        return true;
    }
}