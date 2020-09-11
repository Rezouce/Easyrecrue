<?php declare(strict_types=1);

namespace Easyrecrue\HashingAlgorithm;

class Entry
{
    public string $key;
    public $value;

    public function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
