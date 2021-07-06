<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Attributes\Properties;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Required
{
    public function __construct()
    {
    }
}
