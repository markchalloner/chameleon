<?php

class Chameleon extends \ArrayObject implements \Countable, \IteratorAggregate
{
    /** @var Callable */
    private $errorHandler;

    public function __construct()
    {
        parent::__construct();
        $this->errorHandler = set_error_handler([$this, 'handleError'], E_ALL);
        foreach (func_get_args() as $key) {
            $this->offsetSet($key, null);
        }
    }

    public function __destruct()
    {
        set_error_handler($this->errorHandler, E_ALL);
    }

    // Magic methods
    public static function __callStatic($name, $arguments)
    {
        $key = "Chameleon";
        $arguments = array_key_exists(0, $arguments) && array_key_exists($key, $arguments[0]) ?
            (is_array($arguments[0][$key]) ? $arguments[0][$key] : [$arguments[0][$key]]) : [];
        $reflection = new \ReflectionClass(__CLASS__);
        return $reflection->newInstanceArgs($arguments);
    }

    public function __call($name, $arguments)
    {
        return clone $this;
    }

    public function __invoke()
    {
        return clone $this;
    }

    public function __toString()
    {
        return '1';
    }

    public function __isset($name)
    {
        $this->offsetSet($name, null);
        return true;
    }

    public function __get($name)
    {
        $this->offsetSet($name, null);
        return clone $this;
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, null);
    }

    // ArrayAccess
    public function offsetExists($offset)
    {
        $this->offsetSet($offset, null);
        return true;
    }

    public function offsetGet($offset)
    {
        $this->offsetSet($offset, null);
        return clone $this;
    }

    public function offsetSet($offset, $value)
    {
        parent::offsetSet($offset, clone $this);
    }

    // Countable
    public function count()
    {
        return parent::count() ?: 1;
    }

    // IteratorAggregate
    public function getIterator() {
        $it = parent::getIterator();
        if ($it->valid()) {
            return $it;
        }
        return new ArrayIterator([0 => new self]);
    }

    // Error handling
    public function handleError($errorNumber, $errorString, $errorFile, $errorLine)
    {
        if (!(error_reporting() & $errorNumber)) {
            return false;
        }
        // Ignore type casting errors
        if (strpos($errorString, 'Chameleon could not be converted to ') !== -1) {
            return true;
        }
        // Call previous error handler
        if ($this->errorHandler) {
            return call_user_func($this->errorHandler, $errorNumber, $errorString, $errorFile, $errorLine);
        }
        return false;
    }
}
