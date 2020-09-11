<?php declare(strict_types=1);

namespace Easyrecrue\HashingAlgorithm\KeyHasher;

class BitHasherCode implements KeyHasher
{
    public function hash(string $key): int
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
    private function overflowProtection($hash): int
    {
        return $hash & 0xffffffff;
    }
}
