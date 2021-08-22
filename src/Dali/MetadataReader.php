<?php

namespace Minormous\Metabolize\Dali;

use ReflectionClass;
use ReflectionProperty;
use ReflectionAttribute;
use ReflectionNamedType;
use ReflectionUnionType;
use Minormous\Metabolize\Dali\Metadata;
use Minormous\Metabolize\Dali\ColumnMetadata;
use Minormous\Metabolize\Dali\Attributes\BaseAttribute;
use Minormous\Metabolize\Dali\Attributes\Cast;
use Minormous\Metabolize\Dali\Attributes\Column;
use Minormous\Metabolize\Dali\Attributes\Source;
use Minormous\Metabolize\Dali\Attributes\Table;
use Minormous\Metabolize\Exceptions\InvalidEntityException;

final class MetadataReader
{
    public function __construct(
        private array $implicitCasts = [],
    ) {
    }

    public function read(string $class): Metadata
    {
        $table = null;
        $source = null;
        $repositoryClass = null;
        $propertyColumnMap = null;

        $refClass = new ReflectionClass($class);
        $attributes = $refClass->getAttributes(BaseAttribute::class, ReflectionAttribute::IS_INSTANCEOF);
        foreach ($attributes as $attr) {
            $attr = $attr->newInstance();
            if ($attr instanceof Table) {
                $table = $attr->name;
            } elseif ($attr instanceof Source) {
                $source = $attr->name;
                $repositoryClass = $attr->repositoryClass;
            }
        }
        $propertyColumnMap = $this->getProperties($refClass);

        return new Metadata($class, $table, $source, $repositoryClass, $propertyColumnMap);
    }

    /**
     * @return array<string,ColumnMetadata>
     */
    private function getProperties(ReflectionClass $refClass): array
    {
        $identifierDefined = false;
        $map = [];
        foreach ($refClass->getProperties() as $prop) {
            [$name, $isIdentifier, $castClass] = $this->processColumn($prop, $identifierDefined);
            $type = $this->processColumnType($prop, $castClass);

            $map[$prop->getName()] = new ColumnMetadata($name, $isIdentifier, $type, $castClass);
        }

        return $map;
    }

    private function processColumn(ReflectionProperty $prop, bool &$identifierDefined): array
    {
        $name = $prop->getName();
        $castClass = '';
        $isIdentifier = false;

        $attributes = $prop->getAttributes(BaseAttribute::class, ReflectionAttribute::IS_INSTANCEOF);
        foreach ($attributes as $attr) {
            $attr = $attr->newInstance();
            if ($attr instanceof Column) {
                if ($attr->name !== '') {
                    $name = $attr->name;
                }
                $isIdentifier = $attr->isIdentifier;
                if ($identifierDefined && $isIdentifier) {
                    throw InvalidEntityException::identifierAlreadyDefined($prop);
                }
                $identifierDefined = true;
            } elseif ($attr instanceof Cast) {
                $castClass = $attr->class;
            }
        }

        return [$name, $isIdentifier, $castClass];
    }

    private function processColumnType(ReflectionProperty $prop, string $castClass): string
    {
        $refType = $prop->getType();

        if ($refType instanceof ReflectionUnionType) {
            throw InvalidEntityException::noUnionType($prop);
        }
        if (!$refType instanceof ReflectionNamedType) {
            throw InvalidEntityException::typeNotDefined($prop);
        }

        $type = $refType->getName();
        if (!$refType->isBuiltin()) {
            if (!$castClass && !isset($this->implicitCasts[$type])) {
                throw InvalidEntityException::castRequired($prop);
            }
        }

        return $type;
    }
}
