<?php

namespace Volistx\FrameworkKernel\Tests;

use PHPUnit\Framework\Attributes\Test;
use Volistx\FrameworkKernel\Helpers\KeysCenter;

class KeysCenterTest extends TestCase
{
    #[Test]
    public function test_random_key()
    {
        $length = 64;
        $key = KeysCenter::randomKey($length);

        $this->assertEquals($length, strlen($key));
        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]+$/', $key);
    }

    #[Test]
    public function test_random_salted_key()
    {
        $keyLength = 64;
        $saltLength = 16;
        $result = KeysCenter::randomSaltedKey($keyLength, $saltLength);

        $this->assertArrayHasKey('key', $result);
        $this->assertArrayHasKey('salt', $result);
        $this->assertEquals($keyLength, strlen($result['key']));
        $this->assertEquals($saltLength, strlen($result['salt']));
        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]+$/', $result['key']);
        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]+$/', $result['salt']);
    }
}
