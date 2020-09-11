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
}
