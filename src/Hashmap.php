<?php declare(strict_types=1);

namespace Easyrecrue;

class Hashmap
{
    private string $functionNameForKeyHash;

    private array $data = [];

    public function __construct(string $functionNameForKeyHash)
    {
        $this->functionNameForKeyHash = $functionNameForKeyHash;
    }

    public function add($value): self
    {
        $function = $this->functionNameForKeyHash;

        $this->data[$function($value)] = $value;

        return $this;
    }

    public function find(string $key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        throw new ValueNotFoundException(sprintf("Couldn't find a value for the key '%s'.", $key));
    }
}
