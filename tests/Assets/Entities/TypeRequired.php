<?php

namespace Tests\Metabolize\Assets\Entities;

use Minormous\Metabolize\Dali\Attributes\Column;
use Minormous\Metabolize\Dali\Attributes\Source;
use Minormous\Metabolize\Dali\Attributes\Table;

#[Table('test')]
#[Source('source', TestEntity::class)]
class TypeRequired
{
    #[Column(isIdentifier: true)]
    public int $id;

    #[Column('test_column')]
    public $testColumn;
}
