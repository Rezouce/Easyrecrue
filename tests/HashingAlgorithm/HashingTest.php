<?php

namespace Easyrecrue\Tests\HashingAlgorithm;

use Easyrecrue\HashingAlgorithm\HashingAlgorithm;
use PHPUnit\Framework\TestCase;

abstract class HashingTest extends TestCase
{
    abstract public function getHashingAlgorithm(): HashingAlgorithm;

    public function test_it_can_get_and_set_a_value()
    {
        $hashing = $this->getHashingAlgorithm();

        $this->assertFalse($hashing->has('key'));

        $hashing->set('key', 'value');

        $this->assertTrue($hashing->has('key'));
        $this->assertEquals('value', $hashing->get('key'));
    }
}
