<?php

namespace app\Code\Example\LayerSuperType;

use InvalidArgumentException;

class DomainObject
{
    public function __construct(
        private int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        if (!$id) {
            throw new InvalidArgumentException('ID cannot be null');
        }

        $this->id = $id;
    }
}
