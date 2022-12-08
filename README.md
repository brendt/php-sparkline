# Generate sparkline SVGs in PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/brendt/php-sparkline.svg?style=flat-square)](https://packagist.org/packages/brendt/php-sparkline)
[![Tests](https://github.com/brendt/php-sparkline/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/brendt/php-sparkline/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/brendt/php-sparkline.svg?style=flat-square)](https://packagist.org/packages/brendt/php-sparkline)

This is where your description should go. Try and limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require brendt/php-sparkline
```

## Usage

```php
$sparkLine = SparkLine::new($days);

$total = $sparkLine->getTotal();
$period = $sparkLine->getPeriod();
$svg = $sparkLine->make();
```

![](./.github/img/0.png)

---

```php
$sparkLine = SparkLine::new($days)->withColors('#4285F4', '#31ACF2', '#2BC9F4');
```

![](./.github/img/1.png)

---

```php
$sparkLine = SparkLine::new($days)
    ->withStrokeWidth(4)
    ->withDimensions(500, 100)
    ->withMaxItemAmount(100)
    ->withMaxValue(20);
```

![](./.github/img/2.png)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Brent Roose](https://github.com/brendt)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
