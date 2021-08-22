<?php

namespace Minormous\Metabolize\Dali\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column extends BaseAttribute
{
    public function __construct(
        public string $name = '',
        public bool $isIdentifier = false,
    ) {
    }
}
