<?php

namespace Minormous\Metabolize\Dali\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Source extends BaseAttribute
{
    public function __construct(
        public string $name,
        public string $repositoryClass,
    ) {
    }
}
