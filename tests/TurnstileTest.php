<?php

namespace Panfu\Laravel\Turnstile\Test;

use Panfu\Laravel\Turnstile\Turnstile;
use PHPUnit\Framework\TestCase;

class TurnstileTest extends TestCase
{
    public function test_validation_passes(): void
    {
        $turnstile = new Turnstile('1x0000000000000000000000000000000AA');
        $this->assertTrue($turnstile->validate('XXXX.DUMMY.TOKEN.XXXX'));
    }

    public function test_validation_fails(): void
    {
        $turnstile = new Turnstile('2x0000000000000000000000000000000AA');
        $this->assertFalse($turnstile->validate('XXXX.DUMMY.TOKEN.XXXX'));
    }

    public function test_empty_token_returns_false(): void
    {
        $turnstile = new Turnstile('1x0000000000000000000000000000000AA');
        $this->assertFalse($turnstile->validate(''));
        $this->assertFalse($turnstile->validate(null));
    }

    public function test_empty_secret_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Turnstile('');
    }
}
