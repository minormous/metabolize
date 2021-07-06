<?php

namespace Tests\Metabolize\Api\Validation;

use Laminas\Diactoros\ServerRequest;
use Minormous\Metabolize\Api\Validation\RequestValidator;
use Minormous\Metabolize\Api\Validation\ValidationLoader;
use PHPUnit\Framework\TestCase;
use Tests\Metabolize\Assets\TestInputDefinition;

class ValidationLoaderTest extends TestCase
{
    public function testValidatorValid()
    {
        $validationLoader = new ValidationLoader();
        $validator = $validationLoader->buildValidator(TestInputDefinition::class);
        $this->assertInstanceOf(RequestValidator::class, $validator);

        $req = (new ServerRequest())->withAttribute('INPUT_FIELDS', [
            'accountId' => 1,
            'something' => 'test',
            'optional' => false,
        ]);

        $status = $validator->validate($req);
        $this->assertTrue($status->isValid());
        $this->assertEmpty($status->errors());
    }

    public function testValidatorInvalid()
    {
        $validationLoader = new ValidationLoader();
        $validator = $validationLoader->buildValidator(TestInputDefinition::class);
        $this->assertInstanceOf(RequestValidator::class, $validator);

        $req = (new ServerRequest())->withAttribute('INPUT_FIELDS', [
            'accountId' => 'test',
            'optional' => 5.2,
        ])->withMethod('POST');

        $status = $validator->validate($req);
        $this->assertFalse($status->isValid());
        $this->assertArrayHasKey('path', $status->errors());
        $this->assertArrayHasKey('body', $status->errors());
        $this->assertArrayHasKey('something', $status->errors()['body']);
        $this->assertEquals('something must be present', $status->errors()['body']['something']['messages'][0]);
    }

    public function testValidatorValidWithoutOptionalParameter()
    {
        $validationLoader = new ValidationLoader();
        $validator = $validationLoader->buildValidator(TestInputDefinition::class);
        $this->assertInstanceOf(RequestValidator::class, $validator);

        $req = (new ServerRequest())->withAttribute('INPUT_FIELDS', [
            'accountId' => 1,
            'something' => 'test',
        ]);

        $status = $validator->validate($req);
        $this->assertTrue($status->isValid());
        $this->assertEmpty($status->errors());
    }
}
