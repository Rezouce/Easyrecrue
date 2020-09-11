<?php

namespace Easyrecrue\Tests;

use Easyrecrue\Hashmap;
use Easyrecrue\User;
use PHPUnit\Framework\TestCase;

class HashmapTest extends TestCase
{
    public function test_it_can_find_a_scalar_stored_using_the_function_provided_at_construct()
    {
        $hashmap = new Hashmap('md5');
        $hashmap->add('test');
        $hashmap->add('hello');
        $hashmap->add('EASYRECRUE');
        $hashmap->add('magie');
        $hashmap->add('chipie');
        $hashmap->add('France');
        $hashmap->add('Hélicoptère');
        $hashmap->add('Franc');
        $hashmap->add('mage');

        $this->assertEquals('France', $hashmap->find(md5('France')));
    }

    public function test_it_can_find_an_object_stored_using_the_function_provided_at_construct()
    {
        $hashmap = new Hashmap('getId');
        $hashmap->add(new User(1, 'Florian'));
        $hashmap->add(new User(2, 'Quentin'));
        $hashmap->add(new User(3, 'Céline'));
        $hashmap->add(new User(4, 'Virgil'));
        $hashmap->add($expected = new User(5, 'Thibault'));
        $hashmap->add(new User(6, 'Thomas'));

        $this->assertEquals($expected, $hashmap->find(5));
    }
}

function getId(User $user)
{
    return $user->getId();
}
