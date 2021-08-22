<?php

namespace Minormous\Metabolize\Dali\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Table extends BaseAttribute
{
    public function __construct(
        public string $name,
    ) {
    }
}
