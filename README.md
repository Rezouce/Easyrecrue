# Easyrecrue technical test

The goal was to implement a Hashmap class which accept a function name at construct which it will then use to create key to the values we provide it. 
This Hashmap also provides a find method to retrieve a value if we provide a valid key.

Go see [tests\HashmapTest.php](tests\HashmapTest.php) for some working examples.

## Implementations

The Hashmap class can use different hashing algorithm to reach its goal. 3 have been implemented.

### PhpArray
A simple implementation using the PhpArray. It's simplest and also the de facto fastest solution since it uses the PHP native algorithm.

### SimpleQueue
This is an implementation using a queue. Each value is added to the queue and to find a value we browse all the queue until we find the value. This one has the worst read performance.

### CuckooHashing
This is an implementation of the [Cuckoo Hashing](https://www.geeksforgeeks.org/cuckoo-hashing/). It has the advantage to be predictable and configurable for searchs and insertions.
