<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Attributes\Properties;

use Attribute;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validatable;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Validation
{
    /**
     * @param string $type
     *     Either a class-string for a class that is a Respect Rule; Or the name
     *     of an existing Respect validator.
     * @param array<mixed> $parameters Any additional parameters to provide
     *     to the validator function.
     * @param string $message The message to use for this validator. Uses validators
     *     existing message if not defined.
     * @see https://respect-validation.readthedocs.io/en/latest/list-of-rules/
     */
    public function __construct(
        private string $type,
        private array $parameters = [],
        private string $message = '',
    ) {
    }

    /** @psalm-suppress MoreSpecificReturnType */
    public function getRule(): Validatable
    {
        $rule = $this->type;

        if (!class_exists($this->type)) {
            $rule = "Respect\\Validation\\Rules\\{$this->type}";
        }

        if (class_exists($rule)) {
            // not sure how to fix this
            /** @psalm-suppress LessSpecificReturnStatement,MixedMethodCall */
            return new ($rule)(...$this->parameters);
        }

        throw new \RuntimeException('Invalid validation class');
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
