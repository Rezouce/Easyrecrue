<?php

namespace Easyrecrue\HashingAlgorithm;

use SplQueue;

class SimpleQueue implements HashingAlgorithm
{
    private SplQueue $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
    }

    public function set(string $key, $value): HashingAlgorithm
    {
        $this->queue->push(new Entry($key, $value));

        return $this;
    }

    public function get(string $key)
    {
        foreach ($this->queue as $entry) {
            if ($entry->key === $key) {
                return $entry->value;
            }
        }
    }

    public function has(string $key): bool
    {
        foreach ($this->queue as $entry) {
            if ($entry->key === $key) {
                return true;
            }
        }

        return false;
    }
}
