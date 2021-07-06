<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Attributes\Properties;

use Attribute;
use Respect\Validation\Rules\AbstractRule;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Validation
{
    /**
     * @param string $type
     *     Either a class-string for a class that is a Respect Rule; Or the name
     *     of an existing Respect validator.
     * @param array<mixed> $additionalParams Any additional parameters to provide
     *     to the validator function.
     * @param null|string $message The message to use for this validator. Uses validators
     *     existing message if not defined.
     * @see https://respect-validation.readthedocs.io/en/latest/list-of-rules/
     */
    public function __construct(
        private string $type,
        private array $parameters = [],
        private ?string $message = null,
    ) {
    }

    public function getRule(): AbstractRule
    {
        if (class_exists($this->type)) {
            return new ($this->type)(...$this->parameters);
        }

        $rule = "Respect\\Validation\\Rules\\{$this->type}";

        return new $rule(...$this->parameters);
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
