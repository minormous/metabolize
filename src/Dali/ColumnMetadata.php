<?php

namespace Minormous\Metabolize\Dali;

final class ColumnMetadata
{
    /**
     * @param class-string $castClass
     */
    public function __construct(
        private string $name,
        private string $property,
        private bool $identifier,
        private string $type,
        private string $castClass,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function isIdentifier(): bool
    {
        return $this->identifier;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return class-string
     */
    public function getCastClass(): string
    {
        return $this->getCastClass();
    }
}
