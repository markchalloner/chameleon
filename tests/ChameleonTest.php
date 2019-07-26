<?php

namespace Chameleon\Tests;

use Chameleon;

final class ChameleonTest extends \PHPUnit_Framework_TestCase
{
    /** @var Chameleon */
    private $instance;

    /** @var string */
    private $name;

    public function setUp()
    {
        $this->name = uniqid('x', true);
        $this->instance = new Chameleon($this->name);
    }

    public function testBoolean()
    {
        self::assertTrue(
            is_bool(!!$this->instance),
            'Instance cannot be typecast to a boolean'
        );
    }

    public function testString()
    {
        self::assertTrue(
            is_string("$this->instance"),
            'Instance cannot be typecast to a string'
        );
    }

    public function testInteger()
    {
        // Handle expected PHP Notice
        set_error_handler([$this->instance, 'handleError'], E_NOTICE);
        $int = $this->instance|0;
        restore_error_handler();

        self::assertTrue(
            is_int($int),
            'Instance cannot be typecast to an integer'
        );
    }

    public function testFloat()
    {
        // Handle expected PHP Notice
        set_error_handler([$this->instance, 'handleError'], E_NOTICE);
        $float = $this->instance+.0;
        restore_error_handler();

        self::assertTrue(
            is_float($float),
            'Instance cannot be typecast to a float'
        );
    }

    public function testArray()
    {
        self::assertTrue(
            isset($this->instance[$this->name]),
            'Instance element cannot be tested with isset'
        );
        // Access key for array_key_exists.
        $this->instance->{$this->name};
        self::assertTrue(
            array_key_exists($this->name, $this->instance),
            'Instance element cannot be tested with array_key_exists'
        );
        self::assertTrue(
            $this->instance->offsetExists($this->name),
            'Instance element cannot be tested with offsetExists'
        );
        self::assertInstanceOf(
            Chameleon::class,
            $this->instance[$this->name],
            'Instance cannot be read like an array'
        );
    }

    public function testObject()
    {
        self::assertInstanceOf(
            Chameleon::class,
            $this->instance,
            'Instance is not an instance of Chameleon'
        );
    }

    public function testProperty()
    {
        $instance = $this->instance->{$this->name};
        self::assertInstanceOf(
            Chameleon::class,
            $instance,
            'Instance does not have a readable property that is an instance of Chameleon'
        );
        self::assertTrue(
            array_key_exists($this->name, $this->instance),
            'Instance element cannot be tested with array_key_exists'
        );
    }

    public function testMethod()
    {
        // Access key for array_key_exists.
        $this->instance->{$this->name};
        $instance = $this->instance->{(string)$this->name}();
        self::assertInstanceOf(
            Chameleon::class,
            $instance,
            'Instance does not have a readable method that returns an instance of Chameleon'
        );
        self::assertTrue(
            array_key_exists($this->name, $instance),
            'Instance element cannot be tested with array_key_exists'
        );

    }

    public function testStaticMethod()
    {
        $instance = call_user_func([Chameleon::class, "create"], ["Chameleon" => $this->name]);
        self::assertNotNull(
            $instance,
            'Class does not have a readable static method'
        );
        self::assertTrue(
            array_key_exists($this->name, $instance),
            'Instance element cannot be tested with array_key_exists'
        );
    }

    public function testInvoke()
    {
        // Access key for array_key_exists.
        $this->instance->{$this->name};
        // Copy the instance to a local variable as we can't invoke it directly.
        $instance = $this->instance;
        $instance = $instance();
        self::assertInstanceOf(
            Chameleon::class,
            $instance,
            'Instance invocation does not return an instance of Chameleon'
        );
        self::assertTrue(
            array_key_exists($this->name, $instance),
            'Instance element cannot be tested with array_key_exists'
        );
    }

    public function testCount()
    {
        self::assertCount(1,
            $this->instance,
            'Instance does not have a count of 1'
        );
    }

    public function testForeach()
    {
        // Access key for iteration.
        $this->instance->{$this->name};
        $i = 0;
        foreach ($this->instance as $key => $value) {
            $i++;
            self::assertTrue(
                is_string($key),
                'Element key is not a string'
            );
            self::assertInstanceOf(
                Chameleon::class,
                $this->instance,
                'Element value is not an instance of Chameleon'
            );
        }
        self::assertGreaterThan(
            0,
            $i,
            'Instance is not iterated'
        );
    }

    public function testClone()
    {
        // Access key for array_key_exists.
        $this->instance->{$this->name};
        $instance = clone $this->instance;
        self::assertInstanceOf(
            Chameleon::class,
            $instance,
            'Clone is not an instance of Chameleon'
        );
        self::assertTrue(
            array_key_exists($this->name, $instance),
            'Instance element cannot be tested with array_key_exists'
        );
    }

    public function testNull()
    {
        self::assertNull(
            (unset) $this->instance,
            'Instance cannot be typecast to null'
        );
    }
}
