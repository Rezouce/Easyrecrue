<?php

namespace Easyrecrue\Tests\HashingAlgorithm;

use Easyrecrue\HashingAlgorithm\HashingAlgorithm;
use Easyrecrue\HashingAlgorithm\PhpArray;

class PhpArrayTest extends HashingTest
{
    public function getHashingAlgorithm(): HashingAlgorithm
    {
        return new PhpArray();
    }
}
