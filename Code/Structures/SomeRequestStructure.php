<?php

namespace app\Code\Structures;

use app\Code\Assertions\NotBlank;
use app\Code\Assertions\Required;
use app\Code\Attributes\ArrayStructureMap;
use app\Code\Structures\PersonStructure;
use app\Code\Structures\AddressStructure;

class SomeRequestStructure
{
    /**
     * @param PersonStructure[] $objectList
     */
    public function __construct(
        #[NotBlank]
        public string $name,
        #[Required]
        public int $value,
        public AddressStructure $address,
        public array $list,
        #[ArrayStructureMap(className: PersonStructure::class)]
        public array $objectList = [],
    ) {
    }
}