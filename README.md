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
```bash
$aPaczka = new Apaczka();
```

### Services

Return an array with a list of services.
```php
$aPaczka->orders(int $page = 0, int $limit = 10);
```

### Orders

Return json response with the latest orders.
```bash
$aPaczka->orders(int $page = 0, int $limit = 10);
```

### Order

Return json response with the order details.
```bash
$aPaczka->order(int $orderId);
```

### Waybill

Download waybill.
```bash
$aPaczka->downloadWaybill(int $orderId);
```

Store waybill.
```bash
$aPaczka->storeWaybill(int $orderId, $path);
```

### Service structure

Return json response with the service structure.
```bash
$aPaczka->serviceStructure();
```

### Postage points

Return json response with the list of postage points.
```bash
$aPaczka->spoints(string $type);
```

### Turn In

Download turn in.
```bash
$aPaczka->downloadTurnIn(array $orderIds);
```

Store turn in.
```bash
$aPaczka->storeTurnIn(array $orderIds, $path);
```

### Pickup hours

Return json response with the pickup hours.
```bash
$aPaczka->pickupHours(string $postalCode, int $serviceId = null, bool $removeIndex = false);
```

### Order valuation

Return json response of the order valuation.
```bash
$aPaczka->orderValuation(array $order);
```

### Send order

Return json response of the order send.
```bash
$aPaczka->sendOrder(array $order);
```

### Cancel order

Return json response of the order cancel.
```bash
$aPaczka->cancelOrder(int $orderId);
```

## Changelog

Changelog is available [here](CHANGELOG.md).
