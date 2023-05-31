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




## Usage with Tntity classes
You can use Tntity classes to integrate with thingsboard.

#### Tntity Examples
```php
use JalalLinuX\Thingsboard\Entities\DeviceApi;

/** Without Authentication */
DeviceApi::instance()->setAttribute('deviceToken', 'A1_TEST_TOKEN')->postTelemetry([...])

/** With Authentication */
Device::instance()->withUser($tenantUser)->getDeviceById('ca3b8fc0-dcf6-11ed-a299-0f591673a2d6')
Device::instance()->withUser($tenantUser)->setAttribute('id', 'ca3b8fc0-dcf6-11ed-a299-0f591673a2d6')->getDeviceById()
Device::instance(['id' => 'ca3b8fc0-dcf6-11ed-a299-0f591673a2d6'])->withUser($tenantUser)->getDeviceById()
```




## Usage with Helper function

#### Facade Examples

```php
/** Without Authentication */
thingsboard()->deviceApi()->setAttribute('deviceToken', 'A1_TEST_TOKEN')->postDeviceAttributes([...])

/** With Authentication */
thingsboard()->device()->withUser($tenantUser)->getDeviceById('ca3b8fc0-dcf6-11ed-a299-0f591673a2d6')
thingsboard($tenantUser)->device()->setAttribute('id', 'ca3b8fc0-dcf6-11ed-a299-0f591673a2d6')->getDeviceById()
thingsboard()->device(['id' => 'ca3b8fc0-dcf6-11ed-a299-0f591673a2d6'])->withUser($tenantUser)->getDeviceById()
```




## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.




## Credits

- [JalalLinuX](https://github.com/jalallinux)
- [All Contributors](../../contributors)




## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
