<?php

namespace Thingston\Paginator\Adapter;

use ArrayObject;

class ArrayAdapter implements AdapterInterface
{

    /**
     * @var array
     */
    private $data;

    /**
     * Adapter constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Count total number of items from data.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Slice a portion of items from data.
     *
     * @param int $items
     * @param int $offset
     * @return ArrayObject
     */
    public function slice(int $items, int $offset = 0): ArrayObject
    {
        return new ArrayObject(array_slice($this->data, $offset, $items));
    }
}
