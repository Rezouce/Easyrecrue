<?php declare(strict_types=1);

namespace Easyrecrue\HashingAlgorithm;

use SplFixedArray;

/**
 * See https://www.geeksforgeeks.org/cuckoo-hashing/ for more details about this algorithm.
 * We use 2 hash tables each using its own key hashing.
 */
class CuckooHashing implements HashingAlgorithm
{
    private SplFixedArray $hashTable1;
    private SplFixedArray $hashTable2;
    private int $size = 0;
    private int $capacity;

    public function __construct(int $capacity = 16)
    {
        $this->capacity = $capacity;
        $this->hashTable1 = new SplFixedArray($this->capacity);
        $this->hashTable2 = new SplFixedArray($this->capacity);
    }

    public function set(string $key, $value): HashingAlgorithm
    {
        $entry = new Entry($key, $value);

        $index1 = $this->getIndexFor($this->hash1($key));

        if (!$this->hashTable1->offsetExists($index1) || $this->hashTable1->offsetGet($index1)->key === $key) {
            $this->hashTable1->offsetSet($index1, $entry);

            return $this;
        }

        $index2 = $this->getIndexFor($this->hash2($key));

        if (!$this->hashTable2->offsetExists($index2) || $this->hashTable2->offsetGet($index2)->key === $key) {
            $this->hashTable2->offsetSet($index2, $entry);

            return $this;
        }

        $oldEntry = $this->hashTable1->offsetGet($index1);
        $this->hashTable1->offsetSet($index1, $entry);
        $counter = 0;
        $hashTableNumber = 1;

        while ($oldEntry !== null && $counter < $this->size + 1) {
            if ($hashTableNumber === 0) {
                $index = $this->getIndexFor($this->hash1($oldEntry->key));
                $entry = $oldEntry;
                $oldEntry = $this->hashTable1->offsetGet($index);
                $this->hashTable1->offsetSet($index, $entry);
            } else {
                $index = $this->getIndexFor($this->hash2($oldEntry->key));
                $entry = $oldEntry;
                $oldEntry = $this->hashTable2->offsetGet($index);
                $this->hashTable2->offsetSet($index, $entry);
            }

            $hashTableNumber = 1 - $hashTableNumber;
            ++$counter;
        }

        if ($counter > $this->size) {
            $this->rehash();

            $this->set($oldEntry->key, $oldEntry->value);
        }

        ++$this->size;

        return $this;
    }

    public function get(string $key)
    {
        return $this->hasInHashTable1($key)
            ? $this->hashTable1->offsetGet($this->getIndexFor($this->hash1($key)))->value
            : $this->hashTable2->offsetGet($this->getIndexFor($this->hash2($key)))->value;
    }

    public function has(string $key): bool
    {
        return $this->hasInHashTable1($key) || $this->hasInHashTable2($key);
    }

    private function hasInHashTable1(string $key): bool
    {
        $index = $this->getIndexFor($this->hash1($key));

        return $this->hashTable1->offsetExists($index)
            && $this->hashTable1->offsetGet($index)->key === $key;
    }

    private function hasInHashTable2(string $key): bool
    {
        $index = $this->getIndexFor($this->hash2($key));

        return $this->hashTable2->offsetExists($index)
            && $this->hashTable2->offsetGet($index)->key === $key;
    }

    private function getIndexFor(int $hashValue): int
    {
        return $hashValue % $this->capacity;
    }

    /**+
     * This is basically the String.hashCode implementation from Java.
     */
    private function hash1(string $key): int
    {
        $hash = 0;
        $stringLength = strlen($key);

        for ($i = 0; $i < $stringLength; ++$i) {
            /**
             * The value 31 was chosen because it is an odd prime number.
             * A nice property of 31 is that the multiplication can be replaced by
             * a shift and a subtraction for better performance: 31 * i == (i << 5) - i.
             * Modern VMs do this sort of optimization automatically.
             */
            $hash = $this->overflowProtection(31 * $hash + ord($key[$i]));
        }

        return $hash;
    }

    /**+
     * Another hash implementation found online.
     */
    private function hash2(string $key): int
    {
        $hash = 7;
        $stringLength = strlen($key);

        for ($i = 0; $i < $stringLength; ++$i) {
            $hash ^= $this->overflowProtection($hash << 5 + ord($key[$i]) + $hash >> 2);
        }

        return $hash;
    }

    /**
     * The integer are limited in size, if the value extends the limit, then PHP uses a double isntead of an int.
     * By using the maximum 32 bits value in hexadecimal form with the AND bit operator, we ensure we'll have an int.
     */
    private function overflowProtection($hashValue): int
    {
        return $hashValue & 0xffffffff;
    }

    private function rehash(): void
    {
        $oldHashTable1 = $this->hashTable1;
        $oldHashTable2 = $this->hashTable2;

        $this->capacity *= 2;
        $this->size = 0;

        $this->hashTable1 = new SplFixedArray($this->capacity);
        $this->hashTable2 = new SplFixedArray($this->capacity);

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
