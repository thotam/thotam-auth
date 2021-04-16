# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thotam/thotam-auth.svg?style=flat-square)](https://packagist.org/packages/thotam/thotam-auth)
[![Build Status](https://img.shields.io/travis/thotam/thotam-auth/master.svg?style=flat-square)](https://travis-ci.org/thotam/thotam-auth)
[![Quality Score](https://img.shields.io/scrutinizer/g/thotam/thotam-auth.svg?style=flat-square)](https://scrutinizer-ci.com/g/thotam/thotam-auth)
[![Total Downloads](https://img.shields.io/packagist/dt/thotam/thotam-auth.svg?style=flat-square)](https://packagist.org/packages/thotam/thotam-auth)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require thotam/thotam-auth
```

## Usage

#### Public ThotamAuthProvider

```php
php artisan vendor:publish --provider="Thotam\ThotamAuth\ThotamAuthServiceProvider" --force
```

#### Public FortifyProvider

```php
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
```

```php
Add "phone", "active" to fillable of User Models
```

```php
Add "App\Providers\FortifyServiceProvider::class" to "config\app.php"
```

#### Add CheckAccount Middleware

```php
Add 'CheckAccount' => Thotam\ThotamAuth\Http\Middleware\CheckAccount::Class To App\Http\Kernel.php in $routeMiddleware
```

#### Next, you should migrate your database:

```php
php artisan migrate
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email thanhtamtqno1@gmail.com instead of using the issue tracker.

## Credits

-   [thotam](https://github.com/thotam)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
