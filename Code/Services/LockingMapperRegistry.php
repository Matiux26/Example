<?php

namespace app\Code\Services;

class LockingMapperRegistry implements MappingRegistry
{
    public function __construct(
        private ExclusiveReadOrderLock $orderLockService,
    ) {
    }

    /**
     * @var Mapper[] $mappers
     */
    private array $mappers = [];

    public function registerMappers(string $className, Mapper $mapper): void
    {
        $this->mappers[$className] = new LockingMapper($mapper, $this->orderLockService);
    }

    public function getMapper(string $className): ?Mapper
    {
        return $this->mappers[$className] ?? null;
    }
}