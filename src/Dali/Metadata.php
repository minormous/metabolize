<?php

namespace Minormous\Metabolize\Dali;

final class Metadata
{
    /**
     * @param array<string,ColumnMetadata> $propertyColumnMap
     */
    public function __construct(
        private string $class,
        private string $table,
        private string $source,
        private string $repositoryClass,
        private array $propertyColumnMap,
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getRepositoryClass(): string
    {
        return $this->repositoryClass;
    }

    public function getIdColumn(): ?ColumnMetadata
    {
        foreach ($this->propertyColumnMap as $columnMetadata) {
            if ($columnMetadata->isIdentifier()) {
                return $columnMetadata;
            }
        }

        return null;
    }

    public function getColumn(string $propertyName): ColumnMetadata
    {
        return $this->propertyColumnMap[$propertyName];
    }

    public function getPropertyColumnMap(): array
    {
        return $this->propertyColumnMap;
    }
}
