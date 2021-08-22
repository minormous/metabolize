<?php

namespace Tests\Metabolize\Dali;

use Minormous\Metabolize\Dali\MetadataReader;
use Minormous\Metabolize\Exceptions\InvalidEntityException;
use PHPUnit\Framework\TestCase;
use Tests\Metabolize\Assets\Entities\CastRequired;
use Tests\Metabolize\Assets\Entities\TestEntity;
use Tests\Metabolize\Assets\Entities\TooManyIds;
use Tests\Metabolize\Assets\Entities\TypeRequired;
use Tests\Metabolize\Assets\Entities\UnionType;

class MetadataReaderTest extends TestCase
{
    public function testTooManyIdsThrows()
    {
        $this->expectException(InvalidEntityException::class);
        $this->expectExceptionMessage('Multiple identifiers defined for class');

        $metadataReader = new MetadataReader();
        $metadataReader->read(TooManyIds::class);
    }

    public function testUnionTypeInColumnThrows()
    {
        $this->expectException(InvalidEntityException::class);
        $this->expectExceptionMessage('Property [testColumn] cannot be a union type.');

        $metadataReader = new MetadataReader();
        $metadataReader->read(UnionType::class);
    }

    public function testTypeRequiredThrows()
    {
        $this->expectException(InvalidEntityException::class);
        $this->expectExceptionMessage('Property [testColumn] does not have a type.');

        $metadataReader = new MetadataReader();
        $metadataReader->read(TypeRequired::class);
    }

    public function testCastRequiredThrows()
    {
        $this->expectException(InvalidEntityException::class);
        $this->expectExceptionMessage('Property [testColumn] cannot be implicitly cast, cast definition required.');

        $metadataReader = new MetadataReader();
        $metadataReader->read(CastRequired::class);
    }

    public function testSuccessfulRead()
    {
        $metadataReader = new MetadataReader();
        $result = $metadataReader->read(TestEntity::class);
        $this->assertCount(2, $result->getPropertyColumnMap());
        $idColumn = $result->getIdColumn();
        $this->assertEquals('id', $idColumn->getName());
        $this->assertEquals('int', $idColumn->getType());
    }
}
