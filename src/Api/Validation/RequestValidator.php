<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Validation;

use Minormous\Metabolize\Api\Attributes\Path;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use Respect\Validation\Exceptions\KeyException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;
use Respect\Validation\Validator;
use RuntimeException;

final class RequestValidator
{
    private Validator $pathValidator;
    private Validator $otherValidator;

    /**
     * @param Validatable[] $pathParameters
     * @param Validatable[] $otherParameters
     */
    public function __construct(
        array $pathParameters,
        array $otherParameters,
    ) {
        $this->pathValidator = new Validator(...$pathParameters);
        $this->otherValidator = new Validator(...$otherParameters);
    }

    public function validate(ServerRequestInterface $request): ValidationStatus
    {
        $fields = $request->getAttribute('INPUT_FIELDS');

        if (!\is_array($fields)) {
            throw new RuntimeException('Request requires an INPUT_FIELDS attribute that is an array.');
        }

        $errors = [];
        $bodyType = 'body';
        if ($request->getMethod() === Path::GET) {
            $bodyType = 'query';
        }

        $pathErrors = $this->validatePath($fields);
        if (!empty($pathErrors)) {
            $errors['path'] = $pathErrors;
        }
        $bodyErrors = $this->validateBody($fields);
        if (!empty($bodyErrors)) {
            $errors[$bodyType] = $bodyErrors;
        }

        return new ValidationStatus($errors);
    }

    private function validatePath(array $pathProps): array
    {
        $result = [];
        try {
            $this->pathValidator->assert($pathProps);
        } catch (NestedValidationException $e) {
            $result = $this->convertExceptionToMessages($e);
        }

        return $result;
    }

    private function validateBody(array $bodyProps): array
    {
        $result = [];
        try {
            $this->otherValidator->assert($bodyProps);
        } catch (NestedValidationException $e) {
            $result = $this->convertExceptionToMessages($e);
        }

        return $result;
    }

    private function convertExceptionToMessages(NestedValidationException $e): array
    {
        $result = [];

        foreach ($e->getChildren() as $item) {
            if (!array_key_exists($item->getId(), $result)) {
                $result[$item->getId()] = [
                    'messages' => [],
                    'exceptions' => [],
                ];
            }
            if ($item instanceof KeyException) {
                $children = \array_values($item->getChildren());
                if (\count($children) !== 1) {
                    $reflect = new ReflectionClass($item);
                    $chooseTemplate = $reflect->getMethod('chooseTemplate');
                    $chooseTemplate->setAccessible(true);
                    $result[$item->getId()]['messages'][] = $item->getMessage();
                    $result[$item->getId()]['exceptions'][] = [
                        'name' => $reflect->getShortName(),
                        'params' => $item->getParams(),
                        'template' => $chooseTemplate->invoke($item),
                    ];
                    continue;
                }
                /** @var \Respect\Validation\Exceptions\AllOfException $allOf */
                $allOf = $children[0];
                foreach ($allOf->getChildren() as $child) {
                    $reflect = new ReflectionClass($child);
                    $chooseTemplate = $reflect->getMethod('chooseTemplate');
                    $chooseTemplate->setAccessible(true);
                    $result[$item->getId()]['messages'][] = $child->getMessage();
                    $result[$item->getId()]['exceptions'][] = [
                            'name' => $reflect->getShortName(),
                            'params' => $child->getParams(),
                            'template' => $chooseTemplate->invoke($child),
                        ];
                }
            }
        }

        return $result;
    }
}
