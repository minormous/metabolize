<?php

declare(strict_types=1);

namespace Minormous\Metabolize\Api\Validation;

use Minormous\Metabolize\Api\Attributes\Properties\Parameter;
use Minormous\Metabolize\Api\Attributes\Properties\PathParameter;
use Minormous\Metabolize\Api\Attributes\Properties\Required;
use Minormous\Metabolize\Api\Attributes\Properties\Validation;
use Minormous\Metabolize\Exceptions\InvalidInputDefinitionException;
use ReflectionClass;
use ReflectionProperty;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Rules\Key;

final class ValidationLoader
{
    /**
     * Build a validator to validate a request.
     *
     * @psalm-param class-string $inputDefinition
     */
    public function buildValidator(string $inputDefinition): RequestValidator
    {
        $refClass = new ReflectionClass($inputDefinition);
        $properties = $refClass->getProperties();

        /** @var Key[] $pathParameters */
        $pathParameters = [];
        $otherParameters = [];

        foreach ($properties as $property) {
            $parameter = $property->getAttributes(Parameter::class);
            if (count($parameter) > 0) {
                $otherParameters[] = $this->createPropertyValidator($property);
                continue;
            }

            $pathParameter = $property->getAttributes(PathParameter::class);
            if (count($pathParameter) > 0) {
                $pathParameters[] = $this->createPathPropertyValidator($property);
            }
        }

        return new RequestValidator($pathParameters, $otherParameters);
    }

    private function createPathPropertyValidator(ReflectionProperty $property): Key
    {
        $attributes = $property->getAttributes(Validation::class);
        $type = $property->getType();

        if ($type === null) {
            throw InvalidInputDefinitionException::typeNotDefined($property);
        }

        $allOf = new AllOf();

        foreach ($attributes as $attr) {
            $validation = $attr->newInstance();
            $rule = $validation->getRule();
            if ($validation->getMessage() !== '') {
                $rule->setTemplate($validation->getMessage());
            }
            $allOf->addRule($rule);
        }

        return new Key($property->getName(), $allOf, true);
    }

    private function createPropertyValidator(ReflectionProperty $property): Key
    {
        $attributes = $property->getAttributes();
        $type = $property->getType();

        if ($type === null) {
            throw InvalidInputDefinitionException::typeNotDefined($property);
        }

        $allOf = new AllOf();
        $required = false;

        foreach ($attributes as $attr) {
            $name = $attr->getName();
            if ($name === Required::class) {
                $required = true;
            } elseif ($name === Validation::class) {
                /** @var Validation $validation */
                $validation = $attr->newInstance();
                $rule = $validation->getRule();
                if ($validation->getMessage() !== '') {
                    $rule->setTemplate($validation->getMessage());
                }
                $allOf->addRule($rule);
            }
        }

        return new Key($property->getName(), $allOf, $required);
    }
}
