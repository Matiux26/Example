<?php

namespace app\Code\Structures;

use app\Code\Assertions\NotBlank;
use app\Code\Assertions\Required;

class PersonStructure
{
    public function __construct(
        public string $name,
        #[Required]
        public string $surname,
    ) {
    }
}