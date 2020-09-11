<?php declare(strict_types=1);

namespace Easyrecrue\HashingAlgorithm;

use Easyrecrue\HashingAlgorithm\Cuckoo\CuckooHashTable;
use Easyrecrue\HashingAlgorithm\KeyHasher\BitHasherCode;
use Easyrecrue\HashingAlgorithm\KeyHasher\JavaHasherCode;

/**
 * See https://www.geeksforgeeks.org/cuckoo-hashing/ for more details about this algorithm.
 */
class CuckooHashing implements HashingAlgorithm
{
    /** @var CuckooHashTable[]  */
    private array $hashTables;
    private int $size = 0;

    public function __construct(array $hashTables = [])
    {
        if (empty($hashTables)) {
            $hashTables = [
                new CuckooHashTable(1024, new JavaHasherCode()),
                new CuckooHashTable(1024, new BitHasherCode()),
            ];
        }

        $this->hashTables = $hashTables;
    }

    public function set(string $key, $value): HashingAlgorithm
    {
        $entry = new Entry($key, $value);

        foreach ($this->hashTables as $hashTable) {
            if (!$hashTable->hasKey($key) || $hashTable->hasValueForKey($key)) {
                $hashTable->set($entry);

                return $this;
            }
        }

        $oldEntry = current($this->hashTables)->get($key);
        current($this->hashTables)->set($entry);
        $counter = 0;
        $numberHashTables = count($this->hashTables);
        $hashTableIndexToUse = $numberHashTables > 1 ? 1 : 0;

        while ($oldEntry !== null && $counter < $this->size + 1) {
            $entry = $oldEntry;
            $oldEntry = $this->hashTables[$hashTableIndexToUse]->get($entry->key);
            $this->hashTables[$hashTableIndexToUse]->set($entry);

            $hashTableIndexToUse = ($hashTableIndexToUse + 1) % $numberHashTables;
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
        foreach ($this->hashTables as $hashTable) {
            if ($hashTable->hasValueForKey($key)) {
                return $hashTable->get($key)->value;
            }
        }

        return null;
    }

    public function has(string $key): bool
    {
        foreach ($this->hashTables as $hashTable) {
            if ($hashTable->hasValueForKey($key)) {
                return true;
            }
        }

        return false;
    }

    private function rehash(): void
    {
        $this->size = 0;

        $oldHashTables = $this->hashTables;
        $this->hashTables = [];

        foreach ($oldHashTables as $hashTable) {
            $this->hashTables[] = $hashTable->createNew($hashTable->getCapacity() * 2);
        }

        foreach ($oldHashTables as $hashTable) {
            foreach ($hashTable as $entry) {
                if ($entry) {
                    $this->set($entry->key, $entry->value);
                }
            }
        }
    }
}
