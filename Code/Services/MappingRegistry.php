<?php

namespace app\Code\Services;

interface MappingRegistry
{
    public function registerMappers(string $className, Mapper $mapper);
    public function getMapper(string $className): ?Mapper;
}