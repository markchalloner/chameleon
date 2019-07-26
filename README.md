# Chameleon

PHP object that can be implicitly cast to other types.

``` php
new \Chameleon(mixed $...);
```

`mixed[] ...` keys used by `array_key_exists` and `foreach` (values are always instances of Chameleon).

Example:

```php
<?php
$var = new \Chameleon;
```

## Boolean
Chameleon as a boolean

``` php
echo gettype(!!$var).PHP_EOL;
```

## String
Chameleon as a string

``` php
echo gettype("$var").PHP_EOL;
```

## Integer
Chameleon as an integer

``` php
echo gettype($var|0).PHP_EOL;
```

## Float
Chameleon as a float

``` php
echo gettype($var+.0).PHP_EOL;
```

## Array access
Chameleon can be accessed like an array and has infinite elements of type Chameleon

``` php
echo get_class($var[0]).PHP_EOL;
```

_Note: To ensure array_key_exists returns true for keys, pass them into the constructor or access them in 
some way._

``` php
$var = new Chameleon(0);
array_key_exists(0, $var); // true: passed in constructor
$var->a;
array_key_exists('a', $var); // true: key has been accessed
$var['b'] = null;
array_key_exists('b', $var); // true: key has been accessed
array_key_exists('c', $var); // false
```

## Multi-dimensional array
Chameleon can be accessed like an multi-dimensional array and has infinite sub-elements of type Chameleon

```php
get_class($var[0]['a']).PHP_EOL;
```

## Object
Chameleon is an object

```php
gettype($var);
```

## Object property
Chameleon has infinite properties of type Chameleon

```php
get_class($var->a);
```

## Object methods
Chameleon has infinite methods with any number of parameters which return type Chameleon

```php
get_class($var->abc(0, 'a'));
```

## Static methods
Chameleon has infinite static methods with any number of parameters which return type Chameleon

``` php
get_class(Chameleon::abc(0, 'a'))
```

_Note: Any static method can be used in place of the constructor and passed keys via a special array 
argument._

```
<?php
$var = Chameleon::create(["Chameleon" => [0]]);
$var->a;
```

## Callable
Chameleon is invokable with any number of parameters and returns type Chameleon

```php
get_class($var(0, 'a'));
```

## Foreach
Chameleon is countable with a single element of key type integer and value of Chameleon by default

```php
foreach ($var as $key => $value) {
    echo gettype($key).' => '.get_class($value).PHP_EOL;
}
```

_Note: To change the keys output in the foreach, pass them into the constructor or access them in some way._

``` php
$var = new Chameleon(0);
$var->a;
$var['b'] = null;
```

## Clone
Chameleon can be cloned and returns a new instance of type Chameleon

``` php
$clone = clone $var;
echo get_class($clone).PHP_EOL;
```

## Null
For completeness Chameleon can be cast to null

``` php
echo gettype((unset) $var).PHP_EOL;
