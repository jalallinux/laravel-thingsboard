# Laravel [ThingsBoard.io](https://thingsboard.io/) Client

<a href="https://github.com/jalallinux/laravel-thingsboard">  
    <p align="center"><img src="resources/asset/thingsboard-laravel.png" width="100%"></p>    
</a>




[![Latest Version on Packagist](https://img.shields.io/packagist/v/jalallinux/laravel-thingsboard.svg?style=flat-square)](https://packagist.org/packages/jalallinux/laravel-thingsboard)
[![Tests](https://github.com/jalallinux/laravel-thingsboard/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/jalallinux/laravel-thingsboard/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/jalallinux/laravel-thingsboard.svg?style=flat-square)](https://packagist.org/packages/jalallinux/laravel-thingsboard)
<!--delete-->
---
ThingsBoard is an open-source IoT platform for data collection, processing, visualization, and device management.
This project is a Laravel Package that provides convenient client SDK for both Device and Gateway APIs.




## Installation

You can install the package via composer:

```bash
composer require jalallinux/laravel-thingsboard
```




## Usage with Facade
You can use facades classes to integrate with thingsboard.

#### Facade Examples
```php
use JalalLinuX\Tntity\Facades\Entities\DeviceApi;

/** Without Authentication */
DeviceApi::setAttribute('deviceToken', 'A1_TEST_TOKEN')->postTelemetry([...])

/** With Authentication */
Device::withUser($tenantUser)->getById('ca3b8fc0-dcf6-11ed-a299-0f591673a2d6')
Device::withUser($tenantUser)->setAttribute('id', 'ca3b8fc0-dcf6-11ed-a299-0f591673a2d6')->getById()
Device::withUser($tenantUser)->fill(['id' => 'ca3b8fc0-dcf6-11ed-a299-0f591673a2d6'])->getById()
```




## Usage with Helper function

#### Facade Examples
```php
/** Without Authentication */
thingsboard()->deviceApi()->setAttribute('deviceToken', 'A1_TEST_TOKEN')->postTelemetry([...])

/** With Authentication */
thingsboard()->device()->withUser($tenantUser)->getById('ca3b8fc0-dcf6-11ed-a299-0f591673a2d6')
thingsboard()->device()->withUser($tenantUser)->setAttribute('id', 'ca3b8fc0-dcf6-11ed-a299-0f591673a2d6')->getById()
thingsboard()->device()->withUser($tenantUser)->fill(['id' => 'ca3b8fc0-dcf6-11ed-a299-0f591673a2d6'])->getById()
```




## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.




## Credits

- [JalalLinuX](https://github.com/jalallinux)
- [All Contributors](../../contributors)




## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
