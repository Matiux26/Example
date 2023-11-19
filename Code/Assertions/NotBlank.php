<?php

namespace app\Code\Assertions;

use Attribute;
use yii\web\BadRequestHttpException;
use app\Code\Assertions\AttributeAssertionInterface;

#[Attribute]
class NotBlank implements AttributeAssertionInterface
{
    public function __construct()
    {
    }

    public function check(mixed $value, string $propertyName): bool
    {
        if (empty($value)) {
            throw new BadRequestHttpException("Property: $propertyName cannot be empty");
        }

        return true;
    }
}