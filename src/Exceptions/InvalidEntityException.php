<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Exceptions;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

final class InvalidEntityException extends AbstractException
{
    public static function typeNotDefined(ReflectionProperty $property): static
    {
        $message = sprintf('Property [%s] does not have a type.', $property->getName());

        return new static($message, 500);
    }

    public static function noUnionType(ReflectionProperty $property): static
    {
        $message = sprintf('Property [%s] cannot be a union type.', $property->getName());

        return new static($message, 500);
    }

    public static function castRequired(ReflectionProperty $property): static
    {
        $message = sprintf('Property [%s] cannot be implicitly cast, cast definition required.', $property->getName());

        return new static($message, 500);
    }

    public static function identifierAlreadyDefined(ReflectionProperty $prop): static
    {
        $class = $prop->getDeclaringClass();
        $message = sprintf('Multiple identifiers defined for class [%s]', $class->getName());

        return new static($message, 500);
    }

    public static function tableUnknown(ReflectionClass $class): static
    {
        $message = sprintf('No table defined for entity [%s]', $class->getName());

        return new static($message, 500);
    }

    public static function sourceUnknown(ReflectionClass $class): static
    {
        $message = sprintf('No source defined for entity [%s]', $class->getName());

        return new static($message, 500);
    }

    public static function repositoryUnknown(ReflectionClass $class): static
    {
        $message = sprintf('No repository defined for entity [%s]', $class->getName());

        return new static($message, 500);
    }
}
