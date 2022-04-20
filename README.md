# aPaczka API Client

API client for aPaczka service.

Based on aPaczka api [doc](https://panel.apaczka.pl/dokumentacja_api_v2.php).

## Requirements

* PHP 8.0 or higher with json extensions.

## Installation

The recommended way to install is through [Composer](http://getcomposer.org).

```bash
$ composer require patryk-sawicki/apaczka
```

## Usage

Class declaration.
```php
$aPaczka = new Apaczka();
```

### Services

Return an array with a list of services.
```php
$aPaczka->services();
```

### Pickups

Return an array with a list of pickups.
```php
$aPaczka->pickups();
```

### Options

Return an array with a list of options.
```php
$aPaczka->options();
```

### pointsType

Return an array with a list of points type.
```php
$aPaczka->pointsType();
```

### Orders

Return json response with the latest orders.
```php
$aPaczka->orders(int $page = 0, int $limit = 10);
```

### Order

Return json response with the order details.
```php
$aPaczka->order(int $orderId);
```

### Waybill

Download waybill.
```php
$aPaczka->downloadWaybill(int $orderId);
```

Store waybill.
```php
$aPaczka->storeWaybill(int $orderId, $path);
```

### Service structure

Return json response with the service structure.
```php
$aPaczka->serviceStructure();
```

### Postage points

Return json response with the list of postage points.
```php
$aPaczka->spoints(string $type);
```

### Turn In

Download turn in.
```php
$aPaczka->downloadTurnIn(array $orderIds);
```

Store turn in.
```php
$aPaczka->storeTurnIn(array $orderIds, $path);
```

### Pickup hours

Return json response with the pickup hours.
```php
$aPaczka->pickupHours(string $postalCode, int $serviceId = null, bool $removeIndex = false);
```

### Order valuation

Return json response of the order valuation.
```php
$aPaczka->orderValuation(array $order);
```

### Send order

Return json response of the order send.
```php
$aPaczka->sendOrder(array $order);
```

### Cancel order

Return json response of the order cancel.
```php
$aPaczka->cancelOrder(int $orderId);
```

## Changelog

Changelog is available [here](CHANGELOG.md).
