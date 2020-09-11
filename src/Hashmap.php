<?php declare(strict_types=1);

namespace Easyrecrue;

class Hashmap
{
    private string $functionNameForKeyHash;

    private array $data = [];

    public function __construct(string $functionNameForKeyHash)
    {
        $this->functionNameForKeyHash = $functionNameForKeyHash;

        if (!function_exists($functionNameForKeyHash)) {
            throw new InvalidFunctionNameException(sprintf("There is no function named '%s'.", $functionNameForKeyHash));
        }
    }

    public function add($value): self
    {
        $function = $this->functionNameForKeyHash;
        $key = $function($value);

        if (empty($this->data[$key])) {
            $this->data[$key] = $value;

            return $this;
        }

        throw new KeyAlreadyUsedException(sprintf("The key '%s' is already used.", $key));
    }

    public function find(string $key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        throw new ValueNotFoundException(sprintf("Couldn't find a value for the key '%s'.", $key));
    }
}
