<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Request
{
    public const JSON = 'application/json';

    public function __construct(
        public string $title,
        public string $contentType = self::JSON,
    ) {
    }
}
