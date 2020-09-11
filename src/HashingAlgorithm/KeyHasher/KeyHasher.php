<?php declare(strict_types=1);

namespace Easyrecrue\HashingAlgorithm\KeyHasher;

interface KeyHasher
{
    public function hash(string $key): int;
}
