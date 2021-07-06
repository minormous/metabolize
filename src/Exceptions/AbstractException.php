<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Exceptions;

use Exception;
use Throwable;

abstract class AbstractException extends Exception
{
    final protected function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    protected function withPrevious(Throwable $previous)
    {
        return new static($this->message, $this->code, $previous);
    }
}
