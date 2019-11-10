Thingston Paginator
===================

PHP paginator class for any suitable source of data using custom adapters.

```php
use Thingston\Paginator\Adapter\ArrayAdapter;
use Thingston\Paginator\Paginator;

$adapter = new ArrayAdapter([...]);
$paginator = new Paginator($adapter);

$paginator->setItemsPerPage(50)->setCurrentPage(2);

foreach ($paginator as $item) {
    // print item details
}

```