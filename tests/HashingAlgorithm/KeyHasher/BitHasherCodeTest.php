<?php

namespace Easyrecrue\Tests\HashingAlgorithm\KeyHasher;

use Easyrecrue\HashingAlgorithm\KeyHasher\BitHasherCode;
use Easyrecrue\HashingAlgorithm\KeyHasher\KeyHasher;

class BitHasherCodeTest extends KeyHasherTest
{

    public function getKeyHasher(): KeyHasher
    {
        return new BitHasherCode();
    }
}
