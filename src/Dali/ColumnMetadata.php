<?php

namespace Minormous\Metabolize\Dali;

final class ColumnMetadata
{
    public function __construct(
        private string $name,
        private bool $identifier,
        private string $type,
        private string $castClass,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isIdentifier(): bool
    {
        return $this->identifier;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
