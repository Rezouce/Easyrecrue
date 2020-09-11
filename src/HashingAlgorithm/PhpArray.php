<?php declare(strict_types=1);

namespace Easyrecrue\HashingAlgorithm;

class PhpArray implements HashingAlgorithm
{
    private array $data = [];

    public function set($key, $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function get($key)
    {
        return $this->data[$key];
    }

    public function has($key): bool
    {
        return isset($this->data[$key]);
    }
}
