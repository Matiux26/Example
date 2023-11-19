<?php

namespace app\Code\Structures;

use app\Code\Assertions\NotBlank;

class AddressStructure
{
    public function __construct(
        #[NotBlank]
        public string $street,
        #[NotBlank]
        public string $city,
    ) {
    }
}