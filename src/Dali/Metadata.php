<?php

namespace Minormous\Metabolize\Dali;

final class Metadata
{
    /**
     * @param class-string $class
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

    /**
     * @return class-string
     */
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

    /**
     * @return array{ColumnMetadata}
     */
    public function getIdColumns(): array
    {
        return array_filter(
            $this->propertyColumnMap,
            fn (ColumnMetadata $meta) => $meta->isIdentifier()
        );
    }

    public function getColumn(string $propertyName): ColumnMetadata
    {
        return $this->propertyColumnMap[$propertyName];
    }

    public function getColumnFromColumnName(string $columnName): ?ColumnMetadata
    {
        foreach ($this->propertyColumnMap as $columnMetadata) {
            if ($columnMetadata->getName() === $columnName) {
                return $columnMetadata;
            }
        }

        return null;
    }

    public function getPropertyColumnMap(): array
    {
        return $this->propertyColumnMap;
    }
}
