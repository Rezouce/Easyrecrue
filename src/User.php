<?php declare(strict_types=1);

namespace Easyrecrue;

class User
{
    private $id;
    private $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
