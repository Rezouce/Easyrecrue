<?php

namespace Easyrecrue\Tests\HashingAlgorithm;

use Easyrecrue\HashingAlgorithm\Cuckoo\CuckooHashTable;
use Easyrecrue\HashingAlgorithm\CuckooHashing;
use Easyrecrue\HashingAlgorithm\HashingAlgorithm;
use Easyrecrue\HashingAlgorithm\KeyHasher\BitHasherCode;
use Easyrecrue\HashingAlgorithm\KeyHasher\JavaHasherCode;

class CuckooHashingTest extends HashingTest
{
    public function getHashingAlgorithm(): HashingAlgorithm
    {
        return new CuckooHashing();
    }

    public function test_it_will_resize_itself_if_there_are_collisitions()
    {
        $hashing = new CuckooHashing();

        $this->assertFalse($hashing->has('key'));

        for ($i = 0; $i < 100; ++$i) {
            $hashing->set('key' . $i, 'value' . $i);
        }

        for ($i = 0; $i < 100; ++$i) {
            $this->assertEquals('value' . $i, $hashing->get('key' . $i));
        }
    }

    public function test_it_can_use_multiple_hash_table_each_using_its_own_hasher()
    {
        $hashing = new CuckooHashing([
            new CuckooHashTable(256, new JavaHasherCode()),
            new CuckooHashTable(1024, new JavaHasherCode()),
            new CuckooHashTable(1024, new BitHasherCode()),
        ]);

        $this->assertFalse($hashing->has('key'));

        for ($i = 0; $i < 100; ++$i) {
            $hashing->set('key' . $i, 'value' . $i);
        }

        for ($i = 0; $i < 100; ++$i) {
            $this->assertEquals('value' . $i, $hashing->get('key' . $i));
        }
    }
}
