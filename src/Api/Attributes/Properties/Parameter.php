<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Attributes\Properties;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Parameter
{
    public function __construct(
        public string $description = '',
        public null|string|array|\JsonSerializable $sample = null,
    ) {
    }
}
