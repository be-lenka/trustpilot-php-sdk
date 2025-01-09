# Trustpilot PHP SDK

TrustPilot API library for PHP

```
composer require be-lenka/trustpilot-php-sdk
```

```php
$trustpilot = new TrustPilot($apiKey, $apiSecret);

$data = [
    'perPage' => 1
];
$reviews = $trustpilot->businessUnit()->getReviews($businessUnit, $data);
```