<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Path
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PATCH = 'PATCH';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';
    public const HEAD = 'HEAD';
    public const OPTIONS = 'OPTIONS';
    public const ANY = 'ANY';

    public function __construct(
        public string $path,
        public string $method = 'GET',
    ) {
    }
}
