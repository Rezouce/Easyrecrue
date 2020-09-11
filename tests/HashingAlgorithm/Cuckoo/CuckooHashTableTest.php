<?php

namespace Easyrecrue\Tests\HashingAlgorithm\Cuckoo;

use Easyrecrue\HashingAlgorithm\Cuckoo\CuckooHashTable;
use Easyrecrue\HashingAlgorithm\Entry;
use Easyrecrue\HashingAlgorithm\KeyHasher\JavaHasherCode;
use PHPUnit\Framework\TestCase;

class CuckooHashTableTest extends TestCase
{
    public function test_it_can_get_and_set_an_entry()
    {
        $cuckooHashTable = new CuckooHashTable(4, new JavaHasherCode());

        $cuckooHashTable->set(new Entry('key', 'value'));

        $this->assertEquals('value', $cuckooHashTable->get('key')->value);
    }

    public function test_it_has_the_size_provided()
    {
        $cuckooHashTable = new CuckooHashTable(4, new JavaHasherCode());

        $this->assertEquals(4, $cuckooHashTable->getCapacity());
    }

    public function test_it_can_check_if_it_has_a_key()
    {
        // We use a high capacity here to avoid having a collision between the 2 key's hash.
        $cuckooHashTable = new CuckooHashTable(1024, new JavaHasherCode());

        $cuckooHashTable->set(new Entry('key', 'value'));

        $this->assertTrue($cuckooHashTable->hasKey('key'));
        $this->assertFalse($cuckooHashTable->hasKey('not the key'));
    }

    public function test_it_can_check_if_it_has_a_value_for_a_given_key()
    {
        $cuckooHashTable = new CuckooHashTable(4, new JavaHasherCode());

        $cuckooHashTable->set(new Entry('key', 'value'));

        $this->assertTrue($cuckooHashTable->hasValueForKey('key'));
        $this->assertFalse($cuckooHashTable->hasValueForKey('not the key'));
    }

    public function test_it_can_create_a_CuckooHashTable_with_the_same_hasher()
    {
        $cuckooHashTable = new CuckooHashTable(4, new JavaHasherCode());

        $newCuckooHashTable = $cuckooHashTable->createNew(256);

        $this->assertEquals(256, $newCuckooHashTable->getCapacity());
    }
}
