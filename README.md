# Odnoklassniki OAuth2 client provider

This package provides [Odnoklassniki](https://ok.ru) integration for [OAuth2 Client](https://github.com/thephpleague/oauth2-client) by the League.

## Installation

```sh
composer require xdrew/oauth2-vkontakte
```

## Configuration

```php
$provider = new xdrew\OAuth2\Client\Provider\Odnoklassniki([
    'clientId'     => '1234567',
    'clientPublic' => 'CBAPKGGLEBACACACA',
    'clientSecret' => 's0meRe4lLySEcRetC0De',
    'redirectUri'  => 'https://example.org/oauth-endpoint',
    'scopes'       => ['GET_EMAIL'], // You should request access to this scope from ok.ru support to get user email
]);
```