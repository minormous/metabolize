<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Validation;

use JsonSerializable;

final class ValidationStatus implements JsonSerializable
{
    private const VALIDATION_OK = 'OK';
    private const VALIDATION_ERROR = 'ERROR';

    public function __construct(
        private array $errors,
        private ?string $warning = null,
    ) {
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function jsonSerialize()
    {
        $result = [
            'status' => $this->isValid() ? self::VALIDATION_OK : self::VALIDATION_ERROR,
        ];
        $result = array_merge($result, $this->errors);

        if ($this->warning !== null) {
            $result['warning'] = $this->warning;
        }

        return $result;
    }
}
