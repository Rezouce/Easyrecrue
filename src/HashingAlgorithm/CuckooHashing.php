<?php declare(strict_types=1);

namespace Easyrecrue\HashingAlgorithm;

use Easyrecrue\HashingAlgorithm\Cuckoo\CuckooHashTable;
use Easyrecrue\HashingAlgorithm\KeyHasher\BitHasherCode;
use Easyrecrue\HashingAlgorithm\KeyHasher\JavaHasherCode;

/**
 * See https://www.geeksforgeeks.org/cuckoo-hashing/ for more details about this algorithm.
 * We use 2 hash tables each using its own key hashing.
 */
class CuckooHashing implements HashingAlgorithm
{
    private CuckooHashTable $hashTable1;
    private CuckooHashTable $hashTable2;
    private int $size = 0;
    private int $capacity;

    public function __construct(int $capacity = 16)
    {
        $this->capacity = $capacity;
        $this->hashTable1 = new CuckooHashTable($capacity, new JavaHasherCode());
        $this->hashTable2 = new CuckooHashTable($capacity, new BitHasherCode());
    }

    public function set(string $key, $value): HashingAlgorithm
    {
        $entry = new Entry($key, $value);

        if (!$this->hashTable1->hasKey($key) || $this->hashTable1->hasValueForKey($key)) {
            $this->hashTable1->set($entry);

            return $this;
        }

        if (!$this->hashTable2->hasKey($key) || $this->hashTable2->hasValueForKey($key)) {
            $this->hashTable2->set($entry);

            return $this;
        }

        $oldEntry = $this->hashTable1->get($key);
        $this->hashTable1->set($entry);
        $counter = 0;
        $hashTableNumber = 1;

        while ($oldEntry !== null && $counter < $this->size + 1) {
            if ($hashTableNumber === 0) {
                $entry = $oldEntry;
                $oldEntry = $this->hashTable1->get($entry->key);
                $this->hashTable1->set($entry);
            } else {
                $entry = $oldEntry;
                $oldEntry = $this->hashTable2->get($entry->key);
                $this->hashTable2->set($entry);
            }

            $hashTableNumber = 1 - $hashTableNumber;
            ++$counter;
        }

        if ($oldEntry !== null) {
            $this->rehash();

            $this->set($oldEntry->key, $oldEntry->value);
        }

        ++$this->size;

        return $this;
    }

    public function get(string $key)
    {
        return $this->hashTable1->hasValueForKey($key)
            ? $this->hashTable1->get($key)->value
            : $this->hashTable2->get($key)->value;
    }

    public function has(string $key): bool
    {
        return $this->hashTable1->hasValueForKey($key) || $this->hashTable2->hasValueForKey($key);
    }

    private function rehash(): void
    {
        $oldHashTable1 = $this->hashTable1;
        $oldHashTable2 = $this->hashTable2;

        $this->capacity *= 2;
        $this->size = 0;

        $this->hashTable1 = $this->hashTable1->createNew($this->capacity);
        $this->hashTable2 = $this->hashTable2->createNew($this->capacity);

        foreach ($oldHashTable1 as $entry) {
            if ($entry) {
                $this->set($entry->key, $entry->value);
            }
        }

        foreach ($oldHashTable2 as $entry) {
            if ($entry) {
                $this->set($entry->key, $entry->value);
            }
        }
    }
}
