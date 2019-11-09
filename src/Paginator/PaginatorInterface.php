<?php

namespace Thingston\Paginator;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Thingston\Paginator\Adapter\AdapterInterface;

interface PaginatorInterface extends Countable, IteratorAggregate
{

    /**
     * Default number of items per page.
     */
    const DEFAULT_ITEMS = 10;

    /**
     * Get adapter.
     *
     * @return AdapterInterface
     */
    public function getAdapter(): AdapterInterface;

    /**
     * Set number of items per page.
     *
     * @param int $items
     * @return PaginatorInterface
     * @throws InvalidArgumentException
     */
    public function setItemsPerPage(int $items): PaginatorInterface;

    /**
     * Get number of items per page.
     *
     * @return int
     */
    public function getItemsPerPage(): int;

    /**
     * Count total number of pages.
     *
     * @return int
     */
    public function countPages(): int;

    /**
     * Set current page number.
     *
     * @param int $page
     * @return PaginatorInterface
     * @throws InvalidArgumentException
     */
    public function setCurrentPage(int $page): PaginatorInterface;

    /**
     * Get current page number.
     *
     * @return int
     */
    public function getCurrentPage(): ?int;

    /**
     * Get first page number or null if no pages.
     *
     * @return int|null
     */
    public function getFirstPage(): ?int;

    /**
     * Get previous page number or null if no pages.
     *
     * @return int|null
     */
    public function getPreviousPage(): ?int;

    /**
     * Get next page number or null if no pages.
     *
     * @return int|null
     */
    public function getNextPage(): ?int;

    /**
     * Get last page number or null if no pages.
     *
     * @return int|null
     */
    public function getLastPage(): ?int;

    /**
     * Get first item on page or null if paginator is empty.
     *
     * @return int|null
     */
    public function getFirstItemOnPage(): ?int;

    /**
     * Get last item on page or null if paginator is empty.
     *
     * @return int|null
     */
    public function getLastItemOnPage(): ?int;
}
