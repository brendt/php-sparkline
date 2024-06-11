# Generate sparkline SVGs in PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/brendt/php-sparkline.svg?style=flat-square)](https://packagist.org/packages/brendt/php-sparkline)
[![Tests](https://github.com/brendt/php-sparkline/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/brendt/php-sparkline/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/brendt/php-sparkline.svg?style=flat-square)](https://packagist.org/packages/brendt/php-sparkline)

PHP-Sparkline generates GitHub style sparkline graphs. Read this guide to know how to use it.

## Installation

You can install the package via composer:

```bash
composer require brendt/php-sparkline
```

## Usage

```php
$sparkLine = SparkLine::new(
    new SparkLineDay(
        count: 1,
    ),
    new SparkLineDay(
        count: 2,
    ),
    // …
));

$total = $sparkLine->getTotal();
$period = $sparkLine->getPeriod(); // Spatie\Period
$svg = $sparkLine->make();
```

![](./.github/img/0.png)

To construct a sparkline, you'll have to pass in a collection of `Brendt\SparkLineDay` objects. This object takes two parameters: a `count`, and a `DateTimeInterface`. You could for example convert database entries like so:

```php
$days = PostVistisPerDay::query()
    ->orderByDesc('day')
    ->limit(20)
    ->get()
    ->map(fn (SparkLineDay $row) => new SparkLineDay(
        count: $row->visits,
        day: Carbon::make($row->day),
    ));
```

In many cases though, you'll want to aggregate data with an SQL query, and convert those aggregations on the fly to `SparkLineDay` objects:

```php
$days = DB::query()
    ->from((new Post())->getTable())
    ->selectRaw('`published_at_day`, COUNT(*) as `visits`')
    ->groupBy('published_at_day')
    ->orderByDesc('published_at_day')
    ->whereNotNull('published_at_day')
    ->limit(20)
    ->get()
    ->map(fn (object $row) => new SparkLineDay(
        count: $row->visits,
        day: Carbon::make($row->published_at_day),
    ));
```

### Customization

This package offers some methods to customize the sparkline. First off, you can pick any amount of colors and the sparkline will automatically generate a gradient from them:

```php
$sparkLine = SparkLine::new($days)->withColors('#4285F4', '#31ACF2', '#2BC9F4');
```

![](./.github/img/1.png)

Next, you can configure a bunch of numbers:

```php
$sparkLine = SparkLine::new($days)
    ->withStrokeWidth(4)
    ->withDimensions(500, 100)
    ->withMaxItemAmount(100)
    ->withMaxValue(20);
```

![](./.github/img/2.png)

- **`withStrokeWidth`** will determine the stroke's width
- **`withDimensions`** will determine the width and height of the rendered SVG
- **`withMaxItemAmount`** will determine how many days will be shown. If you originally passed on more days than this max, then the oldest ones will be omitted. If the max amount is set to a number that's _higher_ than the current amount of days, then the sparkline will contain empty days. By default, the amount of given days will be used. 
- **`withMaxValue`** will set the maximum value of the sparkline. This is useful if you have multiple sparklines that should all have the same scale. By default, the maximum value is determined based on the given days.

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
