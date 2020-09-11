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

    public function test_it_will_resize_itself_if_there_are_collisitions()
    {
        $hashing = $this->getHashingAlgorithm();

        $this->assertFalse($hashing->has('key'));

        for ($i = 0; $i < 100; ++$i) {
            $hashing->set('key' . $i, 'value' . $i);
        }

        for ($i = 0; $i < 100; ++$i) {
            $this->assertEquals('value' . $i, $hashing->get('key' . $i));
        }
    }
}
