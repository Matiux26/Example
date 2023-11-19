<?php

namespace app\Code\Services;

use ReflectionClass;
use ReflectionProperty;
use app\Code\Assertions\AttributeAssertionInterface;
use app\Code\Attributes\ArrayStructureMap;
use Exception;
use ReflectionUnionType;
use yii\web\BadRequestHttpException;

class ApiMapper
{
    public function map(?array $data, string $targetClass): ?object
    {
        $reflection = new ReflectionClass($targetClass);
        
        if (!$reflection->isInstantiable() || !$data) {
            return null;
        }

        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $this->validateSupportedTypes($property);

            $propertyType = $property->getType()?->getName();
            $propertyName = $this->toSnakeCase($property->getName());
            $value = $data[$propertyName] ?? null;

            if ($this->isArrayStructure($property)) {
                $value = $this->mapArrayStructure($property, $propertyName, $value);
            }

            if ($this->isStructure($value, $propertyType)) {
                $value = $this->map($value, $propertyType);
            } else {
                $this->validatePrimitiveTypes($property, $value);
            }

            $this->validatePropertyConstraints($property, $value);

            $targetClassConstructorArgs[] = $value;
        }
        
        return $this->initialiseTargetClass($reflection, $targetClassConstructorArgs);
    }

    private function validatePropertyConstraints(ReflectionProperty $property, mixed $value): void
    {
        $propertyAttributes = $property->getAttributes();

        foreach ($propertyAttributes as $propertyAttribute) {
            $initialisedPropertyAttribute = $propertyAttribute->newInstance();

            if ($initialisedPropertyAttribute instanceof AttributeAssertionInterface) {
                $initialisedPropertyAttribute->check($value, $this->toSnakeCase($property->getName()));
            }
        }
    }

    private function validatePrimitiveTypes(ReflectionProperty $property, mixed $value): void
    {
        $propertyType = $property->getType();
        $propertyTypeName = $propertyType?->getName();

        if ($value === null && $propertyType->allowsNull()) {
            return;
        } elseif ($value === null) {
            throw new BadRequestHttpException(
                sprintf(
                    'Property `%s` cannot be null',
                    $this->toSnakeCase($property->getName())
                )
            );
        }

        $isCorrectType = match ($propertyTypeName) {
            'string' => is_string($value),
            'int' => is_int($value),
            'bool' => is_bool($value),
            'float' => is_float($value),
            'array' => is_array($value),
            'object' => is_object($value),
            default => false,
        };

        if (!$isCorrectType) {
            throw new BadRequestHttpException(
                sprintf(
                    'Property `%s` is of incorrect type. Type should be `%s`',
                    $this->toSnakeCase($property->getName()),
                    $propertyTypeName
                )
            );
        }
    }

    private function mapArrayStructure(ReflectionProperty $property, string $propertyType, mixed $value): array
    {
        $arrayMapAttribute = $property->getAttributes(ArrayStructureMap::class)[0] ?? [];
        $arrayMapParameters = $arrayMapAttribute->getArguments();
        $className = $arrayMapParameters['className'] ?? $arrayMapParameters[0] ?? null;

        if ($className) {
            foreach ($value as $structureValue) {
                $arrayStructures[] = $this->map($structureValue, $className);
            }
        }

        return $arrayStructures ?? [];
    }

    private function initialiseTargetClass(ReflectionClass $reflection, array $targetClassConstructorArgs): object
    {
        return $reflection->newInstanceArgs($targetClassConstructorArgs ?? []);
    }

    private function isArrayStructure(ReflectionProperty $property): bool
    {
        if (isset($property->getAttributes(ArrayStructureMap::class)[0])) {
            return true;
        }
        
        return false;
    }

    private function isStructure(mixed $value, ?string $propertyType): bool
    {
        return is_array($value) && $propertyType && class_exists($propertyType);
    }

    private function validateSupportedTypes(ReflectionProperty $property): void
    {
        if ($property->getType() instanceof ReflectionUnionType) {
            throw new Exception('Union types are not supported');
        }
    }

    private function toSnakeCase(string $value): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $value));
    }
}