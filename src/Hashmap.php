<?php declare(strict_types=1);

namespace Easyrecrue;

use Easyrecrue\HashingAlgorithm\HashingAlgorithm;
use Easyrecrue\HashingAlgorithm\PhpArray;

class Hashmap
{
    private HashingAlgorithm $hashing;

    private string $functionNameForKeyHash;

    public function __construct(string $functionNameForKeyHash)
    {
        $this->hashing = new PhpArray();
        $this->functionNameForKeyHash = $functionNameForKeyHash;

        if (!function_exists($functionNameForKeyHash)) {
            throw new InvalidFunctionNameException(sprintf("There is no function named '%s'.", $functionNameForKeyHash));
        }
    }

    public function add($value): self
    {
        $function = $this->functionNameForKeyHash;
        $key = (string)$function($value);

        if ($this->hashing->has($key)) {
            throw new KeyAlreadyUsedException(sprintf("The key '%s' is already used.", $key));
        }

        $this->hashing->set($key, $value);

        return $this;
    }

    public function find(string $key)
    {
        if (!$this->hashing->has($key)) {
            throw new ValueNotFoundException(sprintf("Couldn't find a value for the key '%s'.", $key));
        }

        return $this->hashing->get($key);
    }
}
