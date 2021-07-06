<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Exceptions;

use ReflectionProperty;

final class InvalidInputDefinitionException extends AbstractException
{
    public static function typeNotDefined(ReflectionProperty $property): static
    {
        $message = sprintf('Property [%s] does not have a type to validate against.', $property->getName());

        return new static($message, 422);
    }

    public static function invalidEntityResponse(string $class): static
    {
        return new static("Invalid Entity Responses, class [{$class}] does not exist.");
    }
}
