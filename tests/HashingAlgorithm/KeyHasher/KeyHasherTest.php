<?php

namespace Easyrecrue\Tests\HashingAlgorithm\KeyHasher;

use Easyrecrue\HashingAlgorithm\KeyHasher\KeyHasher;
use PHPUnit\Framework\TestCase;

abstract class KeyHasherTest extends TestCase
{
    abstract public function getKeyHasher(): KeyHasher;

    public function test_it_returns_an_identical_int_for_a_given_string()
    {
        $strings = ['test', 'hello', 'EASYRECRUE', 'magie', 'chipie', 'France', 'Hélicoptère', 'Franc', 'mage'];

        $hasher = $this->getKeyHasher();

        $keys = [];

        foreach ($strings as $string) {
            $keys[$string] = $hasher->hash($string);
        }

        foreach ($strings as $string) {
            $this->assertEquals($keys[$string], $hasher->hash($string));
        }
    }
}
