<?php

namespace Panfu\Laravel\Turnstile\Test;

use Panfu\Laravel\Turnstile\Turnstile;
use PHPUnit\Framework\TestCase;

class TurnstileTest extends TestCase
{
    public function testValidationPasses(): void
    {
        // Secret key that always passes
        $secret = '1x0000000000000000000000000000000AA';
        $validator = new Turnstile($secret);

        // Validate the response
        $isValid = $validator->validate('dummy', '127.0.0.1');

        $this->assertTrue($isValid);
    }

    public function testValidationFails(): void
    {
        // Secret key that always fails
        $secret = '2x0000000000000000000000000000000AA';
        $validator = new Turnstile($secret);

        // Validate the response
        $isValid = $validator->validate('dummy', '127.0.0.1');

        $this->assertFalse($isValid);
    }

    public function testValidationTokenAlreadySpent(): void
    {
        // Secret key that yields a “token already spent” error
        $secret = '3x0000000000000000000000000000000AA';
        $validator = new Turnstile($secret);

        // Validate the response token
        $isValid = $validator->validate('dummy', '127.0.0.1');
        $this->assertFalse($isValid);
    }
}
