<?php

namespace Tests\Metabolize\Assets\Entities;

use DateTimeImmutable;
use Minormous\Metabolize\Dali\Attributes\Column;
use Minormous\Metabolize\Dali\Attributes\Source;
use Minormous\Metabolize\Dali\Attributes\Table;

#[Source('source', TestEntity::class)]
class MissingTable
{
    #[Column(isIdentifier: true)]
    public int $id;

    #[Column('test_column')]
    public string $testColumn;
}
