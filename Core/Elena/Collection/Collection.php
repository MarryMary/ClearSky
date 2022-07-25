<?php

namespace Clsk\Elena\Collection;

use \IteratorAggregate;
use \ArrayIterator;

final class Collection implements IteratorAggregate
{
    public function __construct(private array $attributes = [])
    {
    }

    /**
     * @return Task[]|ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->attributes);
    }
}