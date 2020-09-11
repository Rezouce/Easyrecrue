<?php declare(strict_types=1);

namespace Easyrecrue\HashingAlgorithm\Cuckoo;

use Easyrecrue\HashingAlgorithm\Entry;
use Easyrecrue\HashingAlgorithm\KeyHasher\KeyHasher;
use IteratorAggregate;
use SplFixedArray;

class CuckooHashTable implements IteratorAggregate
{
    private SplFixedArray $hashTable;
    private KeyHasher $hasher;

    public function __construct(int $capacity, KeyHasher $keyHasher)
    {
        $this->hashTable = new SplFixedArray($capacity);
        $this->hasher = $keyHasher;
    }

    public function set(Entry $entry): CuckooHashTable
    {
        $this->hashTable->offsetSet($this->getIndexFor($entry->key), $entry);

        return $this;
    }

    public function get(string $key): ?Entry
    {
        return $this->hashTable->offsetGet($this->getIndexFor($key));
    }

    public function hasValueForKey(string $key): bool
    {
        $entry = $this->hashTable->offsetGet($this->getIndexFor($key));

        return $entry !== null && $entry->key === $key;
    }

    public function hasKey(string $key): bool
    {
        return $this->hashTable->offsetGet($this->getIndexFor($key)) !== null;
    }

    private function getIndexFor(string $key): int
    {
        return $this->hasher->hash($key) % $this->hashTable->getSize();
    }

    public function createNew($capacity): CuckooHashTable
    {
        return new static($capacity, $this->hasher);
    }

    public function getCapacity(): int
    {
        return $this->hashTable->getSize();
    }

    public function getIterator()
    {
        return $this->hashTable;
    }
}
