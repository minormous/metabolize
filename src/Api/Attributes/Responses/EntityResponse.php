<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Attributes\Responses;

use Attribute;
use Minormous\Metabolize\Exceptions\InvalidInputDefinitionException;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class EntityResponse
{
    /** @psalm-param class-string $class */
    public function __construct(public string $class)
    {
        if (!class_exists($class)) {
            throw InvalidInputDefinitionException::invalidEntityResponse($this->class);
        }
    }
}
