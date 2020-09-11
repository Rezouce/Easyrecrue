<?php

namespace Easyrecrue\Tests;

use Easyrecrue\HashingAlgorithm\CuckooHashing;
use Easyrecrue\Hashmap;
use Easyrecrue\InvalidFunctionNameException;
use Easyrecrue\KeyAlreadyUsedException;
use Easyrecrue\User;
use Easyrecrue\ValueNotFoundException;
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

    public function test_it_throws_an_exception_if_it_cant_find_a_value_matching_the_key()
    {
        $hashmap = new Hashmap('md5');

        $this->expectException(ValueNotFoundException::class);
        $this->expectExceptionMessage("Couldn't find a value for the key 'no-key-matching'.");

        $hashmap->find('no-key-matching');
    }

    /**
     * Since the Hashmap has a add method instead of a put or set, we consider that if a key is already used,
     * we don't override the value but we throw an exception.
     */
    public function test_it_throws_an_exception_if_there_is_a_key_collision()
    {
        $hashmap = new Hashmap('getId');

        $this->expectException(KeyAlreadyUsedException::class);
        $this->expectExceptionMessage("The key '1' is already used.");

        $hashmap
            ->add(new User(1, 'Florian'))
            ->add(new User(1, 'Quentin'));
    }

    public function test_it_throws_an_exception_if_provided_a_function_name_that_doesnt_exist()
    {
        $this->expectException(InvalidFunctionNameException::class);
        $this->expectExceptionMessage("There is no function named 'not_existing_function_name'.");

        new Hashmap('not_existing_function_name');
    }

    public function test_it_is_possible_to_provide_a_hashing_algorithm_to_use()
    {
        $hashmap = new Hashmap('md5', $algorithm = new CuckooHashing());

        $hashmap
            ->add('test')
            ->add('hello');

        $this->assertTrue($algorithm->has(md5('test')));
        $this->assertTrue($algorithm->has(md5('hello')));
        $this->assertFalse($algorithm->has('not existing key'));
    }
}
