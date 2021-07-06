<?php

namespace Tests\Metabolize\Assets;

use Minormous\Metabolize\Api\Attributes\Path;
use Minormous\Metabolize\Api\Attributes\Properties\Parameter;
use Minormous\Metabolize\Api\Attributes\Properties\PathParameter;
use Minormous\Metabolize\Api\Attributes\Properties\Required;
use Minormous\Metabolize\Api\Attributes\Properties\Validation;
use Minormous\Metabolize\Api\Attributes\Request;
use Minormous\Metabolize\Api\Attributes\Responses\EmptyResponse;

#[Request(title: 'Test account')]
#[Path(method: Path::POST, path: '/test/{accountId:\d+}')]
#[EmptyResponse]
class TestInputDefinition
{
    #[Validation('IntVal')]
    #[Validation('Positive')]
    #[PathParameter('Account id', 1)]
    public int $accountId;

    #[Required]
    #[Parameter('some value')]
    #[Validation(type: 'Min', parameters: [2])]
    public string $something;

    #[Parameter('an optional bool val')]
    #[Validation('BoolVal')]
    public bool $optional = false;
}
