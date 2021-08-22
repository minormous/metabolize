<?php

namespace Minormous\Metabolize\Dali\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Cast extends BaseAttribute
{
    public function __construct(
        public string $class = '',
    ) {
    }
}
