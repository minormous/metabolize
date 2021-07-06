<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Attributes\Responses;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class ValidationResponse
{
}
