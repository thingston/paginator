<?php

namespace ThingstonTest\Paginator;

use PHPUnit\Framework\TestCase;
use Thingston\Paginator\Adapter\ArrayAdapter;
use Thingston\Paginator\Paginator;

class PaginatorTest extends TestCase
{

    private $paginator;

    protected function setUp(): void
    {
        $data = [];

        for ($i = 0; $i < rand(100, 200); $i++) {
            $data[] = $i + 1;
        }

        $adapter = new ArrayAdapter($data);
        $this->paginator = new Paginator($adapter);
    }

    public function testCount()
    {
        $this->assertCount($this->paginator->getAdapter()->count(), $this->paginator);
    }

    public function testItemsPerPage()
    {
        $this->assertSame(Paginator::DEFAULT_ITEMS, $this->paginator->getItemsPerPage());

        $items = rand(10, 100);
        $this->paginator->setItemsPerPage($items);

        $this->assertSame($items, $this->paginator->getItemsPerPage());
    }

    public function testCountPages()
    {
        $pages = (int) ceil(count($this->paginator) / $this->paginator->getItemsPerPage());
        $this->assertSame($pages, $this->paginator->countPages());
    }

    public function testCurrentPage()
    {
        $this->assertSame(1, $this->paginator->getCurrentPage());

        $page = rand(1, $this->paginator->countPages());
        $this->paginator->setCurrentPage($page);
        $this->assertSame($page, $this->paginator->getCurrentPage());
    }

    public function testPageIndicators()
    {
        $page = rand(2, $this->paginator->countPages() - 1);
        $this->paginator->setCurrentPage($page);


        $first = 1;
        $last = $this->paginator->countPages();
        $previous = 1 < $page ? $page - 1 : null;
        $next = $last > $page ? $page + 1 : null;

        $this->assertSame($first, $this->paginator->getFirstPage());
        $this->assertSame($previous, $this->paginator->getPreviousPage());
        $this->assertSame($next, $this->paginator->getNextPage());
        $this->assertSame($last, $this->paginator->getLastPage());
    }

    public function testItemIndicators()
    {
        $items = rand(5, 50);
        $this->paginator->setItemsPerPage($items);

        $page = rand(1, $this->paginator->countPages());
        $this->paginator->setCurrentPage($page);

        $first = $page * $items - $items + 1;
        $last = $page * $items;

        if ($last > $count = $this->paginator->count()) {
            $last = $count;
        }

        $this->assertSame($first, $this->paginator->getFirstItemOnPage());
        $this->assertSame($last, $this->paginator->getLastItemOnPage());
    }

    public function testEmptyPaginator()
    {
        $adaptor = new ArrayAdapter([]);
        $paginator = new Paginator($adaptor);

        $this->assertCount(0, $paginator);
        $this->assertSame(0, $paginator->countPages());
        $this->assertNull($paginator->getCurrentPage());
        $this->assertNull($paginator->getFirstPage());
        $this->assertNull($paginator->getPreviousPage());
        $this->assertNull($paginator->getNextPage());
        $this->assertNull($paginator->getLastPage());
        $this->assertNull($paginator->getFirstItemOnPage());
        $this->assertNull($paginator->getLastItemOnPage());

        $iterator = $paginator->getIterator();
        $this->assertInstanceOf(\Traversable::class, $iterator);
        $this->assertEmpty($iterator);

        $this->expectException(\InvalidArgumentException::class);
        $paginator->setCurrentPage(1);
    }

    public function testZeroItemsPerPage()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->paginator->setItemsPerPage(0);
    }

    public function testNegativeItemsPerPage()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->paginator->setItemsPerPage(rand(-100, -1));
    }

    public function testCurrentPageToZero()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->paginator->setCurrentPage(0);
    }

    public function testCurrentPageToNegative()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->paginator->setCurrentPage(rand(-100, -1));
    }

    public function testGetIterator()
    {
        $this->assertInstanceOf(\Traversable::class, $this->paginator->getIterator());
    }
}
