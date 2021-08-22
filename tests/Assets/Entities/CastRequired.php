<?php

namespace Tests\Metabolize\Assets\Entities;

use DateTimeImmutable;
use Minormous\Metabolize\Dali\Attributes\Column;
use Minormous\Metabolize\Dali\Attributes\Source;
use Minormous\Metabolize\Dali\Attributes\Table;

#[Table('test')]
#[Source('source', TestEntity::class)]
class CastRequired
{
    #[Column(isIdentifier: true)]
    public int $id;

    #[Column]
    public DateTimeImmutable $testColumn;
}
