<?php

namespace Thingston\Paginator\Adapter;

use ArrayObject;
use Countable;

interface AdapterInterface extends Countable
{

    /**
     * Slice a portion of items from data.
     *
     * @param int $items
     * @param int $offset
     * @return ArrayObject
     */
    public function slice(int $items, int $offset = 0): ArrayObject;
}
