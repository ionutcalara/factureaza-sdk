# PHP 8.1 SDK for factureaza.ro API

[![Tests](https://img.shields.io/github/workflow/status/artkonekt/factureaza-sdk/tests/master?style=flat-square)](https://github.com/artkonekt/factureaza-sdk/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/konekt/factureaza-sdk.svg?style=flat-square)](https://packagist.org/packages/konekt/factureaza-sdk)
[![Packagist downloads](https://img.shields.io/packagist/dt/konekt/factureaza-sdk.svg?style=flat-square)](https://packagist.org/packages/konekt/factureaza-sdk)
[![StyleCI](https://styleci.io/repos/537435324/shield?branch=master)](https://styleci.io/repos/537435324)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This package provides a PHP SDK for interacting with the [factureaza.ro GraphQL API](https://factureaza.ro/documentatie-api).

> The minimum requirement of this package is PHP 8.1.

## Usage

To connect to the live system, use the `connect` method and pass your api key:

```php
$live = Factureaza::connect('api key here');
$live->myAccount();
// => Konekt\Factureaza\Models\MyAccount
//     id: "555000444",
//     name: "yourcompany",
//     companyName: "Your Company SRL",
//     createdAt: "2019-06-06T16:23:34+03:00",
//     updatedAt: "2022-09-13T08:03:29+03:00"
//     ...
```

To connect to the sandbox system, use the `sandbox` method:

```php
$sandbox = Factureaza::sandbox();
$sandbox->myAccount();
// => Konekt\Factureaza\Models\MyAccount
//     id: "340138083",
//     name: "sandbox",
//     companyName: "Test Services SRL",
//     createdAt: "2014-06-06T16:23:34+03:00",
//     updatedAt: "2022-09-13T08:03:29+03:00"
//     ...
```

### Time Zone

The factureaza.ro API returns dates in the Romanian time zone (Europe/Bucharest).
This SDK returns dates in that timezone by default.

If you want dates to be returned in UTC, call the `useUTCTime()` method:

```php
$factureaza = Factureaza::connect('api key');
$factureaza->myAccount()->createdAt()->toIso8601String();
// 2014-06-06T16:23:34+03:00

$factureaza->useUtcTime();
$factureaza->myAccount()->createdAt()->toIsoString();
// 2014-06-06T13:23:34+00:00
```
