<?php

namespace ThingstonTest\Paginator\Adapter;

use Thingston\Paginator\Adapter\ArrayAdapter;
use PHPUnit\Framework\TestCase;

class ArrayAdapterTest extends TestCase
{

    const COUNT = 1000;

    /**
     * @var array
     */
    private $data;

    /**
     * @var ArrayAdapter
     */
    private $adapter;

    protected function setUp(): void
    {
        $this->data = [];

        for ($i = 1; $i <= self::COUNT; $i++) {
            $this->data[] = $i;
        }

        $this->adapter = new ArrayAdapter($this->data);
    }

    public function testCount()
    {
        $this->assertCount(self::COUNT, $this->adapter);
    }

    public function testSlice()
    {
        $items = rand(1, 100);
        $offset = rand(0, self::COUNT - 100);

        $slice = $this->adapter->slice($items, $offset);
        $this->assertCount($items, $slice);

        for ($i = 0; $i < $items; $i++) {
            $k = $i + $offset;
            $this->assertSame($this->data[$k], $slice[$i]);
        }
    }
}
