<?php

namespace Easyrecrue\Tests\HashingAlgorithm\KeyHasher;

use Easyrecrue\HashingAlgorithm\KeyHasher\JavaHasherCode;
use Easyrecrue\HashingAlgorithm\KeyHasher\KeyHasher;

class JavaHasherCodeTest extends KeyHasherTest
{

    public function getKeyHasher(): KeyHasher
    {
        return new JavaHasherCode();
    }
}
