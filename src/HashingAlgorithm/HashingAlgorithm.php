<?php declare(strict_types=1);

namespace Easyrecrue\HashingAlgorithm;

interface HashingAlgorithm
{
    public function set(string $key, $value): HashingAlgorithm;

    public function get(string $key);

    public function has(string $key): bool;
}