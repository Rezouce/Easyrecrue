<?php

namespace Easyrecrue\Tests\HashingAlgorithm;

use Easyrecrue\HashingAlgorithm\CuckooHashing;
use Easyrecrue\HashingAlgorithm\HashingAlgorithm;

class CuckooHashingTest extends HashingTest
{
    public function getHashingAlgorithm(): HashingAlgorithm
    {
        return new CuckooHashing();
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
