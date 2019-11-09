<?php

namespace Thingston\Paginator;

use ArrayObject;
use InvalidArgumentException;
use Thingston\Paginator\Adapter\AdapterInterface;
use Traversable;

class Paginator implements PaginatorInterface
{

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $itemsPerPage = self::DEFAULT_ITEMS;

    /**
     * @var Traversable
     */
    private $iterator;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get adapter.
     *
     * @return AdapterInterface
     */
    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }

    /**
     * Set number of items per page.
     *
     * @param int $items
     * @return PaginatorInterface
     * @throws InvalidArgumentException
     */
    public function setItemsPerPage(int $items): PaginatorInterface
    {
        if (1 > $items) {
            throw new InvalidArgumentException(sprintf('Invalid number of items per page: %d.', $items));
        }

        $this->itemsPerPage = $items;
        $this->iterator = null;
        $this->currentPage = null;

        return $this;
    }

    /**
     * Get number of items per page.
     *
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Get total number of items to paginate.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->adapter->count();
    }

    /**
     * Count total number of pages.
     *
     * @return int
     */
    public function countPages(): int
    {
        return ceil($this->count() / $this->getItemsPerPage());
    }

    /**
     * Set current page number.
     *
     * @param int $page
     * @return PaginatorInterface
     * @throws InvalidArgumentException
     */
    public function setCurrentPage(int $page): PaginatorInterface
    {
        if (0 === $pages = $this->countPages()) {
            throw new InvalidArgumentException(sprintf('Invalid page number: %d; paginator is empty.', $page));
        }

        if (1 > $page || $pages < $page) {
            throw new InvalidArgumentException(sprintf('Invalid page number: %d; it must be between 1 and %d.', $page, $pages));
        }

        $this->currentPage = $page;
        $this->iterator = null;

        return $this;
    }

    /**
     * Get current page number.
     *
     * @return int
     */
    public function getCurrentPage(): ?int
    {
        if (null === $this->currentPage && 0 < $this->countPages()) {
            $this->currentPage = 1;
        }

        return $this->currentPage;
    }

    /**
     * Get first page number or null if no pages.
     *
     * @return int|null
     */
    public function getFirstPage(): ?int
    {
        if (null === $this->getCurrentPage()) {
            return null;
        }

        return 1;
    }

    /**
     * Get previous page number or null if no pages or current page is first.
     *
     * @return int|null
     */
    public function getPreviousPage(): ?int
    {
        $current = $this->getCurrentPage();

        if (null === $current || 1 === $current) {
            return null;
        }

        return $current - 1;
    }

    /**
     * Get next page number or null if no pages.
     *
     * @return int|null
     */
    public function getNextPage(): ?int
    {
        $current = $this->getCurrentPage();

        if (null === $current || $this->getLastPage() === $current) {
            return null;
        }

        return $current + 1;
    }

    /**
     * Get last page number or null if no pages.
     *
     * @return int|null
     */
    public function getLastPage(): ?int
    {
        if (0 === $last = $this->countPages()) {
            return null;
        }

        return $last;
    }

    /**
     * Get first item on page or null if paginator is empty.
     *
     * @return int|null
     */
    public function getFirstItemOnPage(): ?int
    {
        if (null === $current = $this->getCurrentPage()) {
            return null;
        }

        return $current * $this->itemsPerPage - $this->itemsPerPage + 1;
    }

    /**
     * Get last item on page or null if paginator is empty.
     *
     * @return int|null
     */
    public function getLastItemOnPage(): ?int
    {
        if (null === $first = $this->getFirstItemOnPage()) {
            return null;
        }

        $last = $first + $this->itemsPerPage - 1;

        return min([$last, $this->count()]);
    }

    /**
     * Get iterator.
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        if (null === $this->iterator) {
            if (null === $this->getCurrentPage()) {
                return $this->iterator = new ArrayObject([]);
            }

            $this->iterator = $this->adapter->slice($this->itemsPerPage, $this->getFirstItemOnPage() - 1);
        }

        return $this->iterator;
    }
}
