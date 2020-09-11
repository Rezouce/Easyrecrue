<?php

namespace Easyrecrue\Tests;

use Easyrecrue\HashingAlgorithm\HashingAlgorithm;
use Easyrecrue\HashingAlgorithm\PhpArray;

class PhpArrayTest extends HashingTest
{
    public function getHashingAlgorithm(): HashingAlgorithm
    {
        return new PhpArray();
    }
}
