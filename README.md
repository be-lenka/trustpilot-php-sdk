# Trustpilot PHP SDK

TrustPilot API library for PHP

```
composer require be-lenka/trustpilot-php-sdk
```

### Initialize

```php
use TrustPilot\TrustPilot;

$username = '<login_email>';
$password = '<login_password>';

// https://businessapp.b2b.trustpilot.com/applications/
$apiKey = '<api_key>';
$apiSecret = '<api_secret>';

$trustpilot = new TrustPilot($apiKey, $apiSecret);
$token = $trustpilot
    ->authorize()
    ->createPasswordToken($username, $password)
;
$trustpilot->setToken($token);
```

#### Find businessUnitId

```php
$unit = $trustpilot->businessUnit()->find('belenka.com');   
$businessUnitId = $unit->id;
```

#### Fetch reviews

```php
$data = [
    'perPage' => 1
];
$reviews = $trustpilot->businessUnit()->getReviews($businessUnitId, $data);
```

#### Create invite link

```php
$data = [
    "referenceId" => "INV0001",
    "email" => "test@belenka.com",
    "name" => "John Doe",
    "locale" => "en-US",
    //"tags" => [
    //    "tag1",
    //    "tag2"
    //],
    "redirectUri" => "https://www.belenka.com"
];
$inviteLink = $tp->invitation()->generateInvitationLink($businessUnitId, $data);
```

#### Create invite

```php
// https://businessapp.b2b.trustpilot.com/invitations/editor
$templateId = '<templateId>';
$data = [
    "replyTo" => 'info@belenka.com',
    "locale" => "en-US",
    "referenceId" => "INV0000123",
    "recipientName" => "Majkl Najt",
    "recipientEmail" => 'majkl.najt@belenka.com',
    "type" => "invite",
    "templateId" => $templateId,
    "preferredSendTime" => "09/01/2024 15:07:00",
    "redirectUri" => "https://www.belenka.com",
];
$invite = $tp->invitation()->createEmailInvitation($businessUnitId, $data);
```