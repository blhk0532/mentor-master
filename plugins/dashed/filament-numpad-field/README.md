# A numpad field to enter pricing.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dashed/filament-numpad-field.svg?style=flat-square)](https://packagist.org/packages/dashed/filament-numpad-field)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/dashed/filament-numpad-field/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/dashed/filament-numpad-field/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/dashed/filament-numpad-field/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/dashed/filament-numpad-field/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/dashed/filament-numpad-field.svg?style=flat-square)](https://packagist.org/packages/dashed/filament-numpad-field)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require dashed/filament-numpad-field
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-numpad-field-views"
```

## Usage

```php
use DashedDEV\FilamentNumpadField\NumpadField;

NumpadField::make('price')
    ->label('Prijs')
    ->minCents(0)
    ->maxCents(100000);
```

Add below to your custom theme
```php
@source '../../../../vendor/dashed/**/*.blade.php';
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Robin van Maasakker](https://github.com/Robinvm)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
