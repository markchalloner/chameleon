<?php

class Chameleon implements \ArrayAccess, \Countable, \Iterator
{
    /** @var mixed[] */
    private $keys = [];

    /** @var int */
    private $position = 0;

    /** @var Callable */
    private $errorHandler;

    public function __construct(array $keys = [])
    {
        $this->errorHandler = set_error_handler([$this, 'handleError'], E_ALL);
        $this->keys = $keys;
        foreach ($this->keys as $key) {
            $this->{$key} = new self();
        }
    }

    public function __destruct()
    {
        set_error_handler($this->errorHandler, E_ALL);
    }

    // Magic methods
    public static function __callStatic($name, $arguments)
    {
        return new self(array_key_exists(0, $arguments) ? $arguments[0] : []);
    }

    public function __call($name, $arguments)
    {
        return new self($this->keys);
    }

    public function __invoke()
    {
        return new self($this->keys);
    }

    public function __clone()
    {
        $this->reset();
    }

    public function __toString()
    {
        return '1';
    }

    public function __isset($name)
    {
        return true;
    }

    public function __get($name)
    {
        return new self($this->keys);
    }

    // ArrayAccess
    public function offsetExists($offset)
    {
        return true;
    }

    public function offsetGet($offset)
    {
        return new self($this->keys);
    }

    public function offsetSet($offset, $value)
    {
    }

    public function offsetUnset($offset)
    {
    }

    // Countable
    public function count()
    {
        return count($this->keys ?: [0]);
    }

    // Iterator
    public function rewind()
    {
        $this->position = 0;
        return new self($this->keys);
    }

    public function current()
    {
        return new self($this->keys);
    }

    public function key()
    {
        $keys = $this->keys ?: [0];
        return $keys[$this->position];
    }

    public function next()
    {
        $this->position++;
        return new self($this->keys);
    }

    public function valid()
    {
        return $this->position < $this->count();
    }

    public function reset()
    {
        $this->position = 0;
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
