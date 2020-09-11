<?php declare(strict_types=1);

namespace Easyrecrue\HashingAlgorithm\KeyHasher;

/**+
 * This is basically the String.hashCode implementation from Java.
 */
class JavaHasherCode implements KeyHasher
{
    /**
     * The value 31 was chosen because it is an odd prime number.
     * A nice property of 31 is that the multiplication can be replaced by
     * a shift and a subtraction for better performance: 31 * i == (i << 5) - i.
     * Modern VMs do this sort of optimization automatically.
     */
    private const COLLISION_NUMBER = 31;

    public function hash(string $key): int
    {
        $hash = 0;
        $stringLength = strlen($key);

        for ($i = 0; $i < $stringLength; ++$i) {
            $hash = $this->overflowProtection(static::COLLISION_NUMBER * $hash + ord($key[$i]));
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
