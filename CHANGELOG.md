# Changelog

All notable changes to `php-sparkline` will be documented in this file.

## 2.0.0

- Removed `SparkLine::new()`, use `new SparkLine()` instead
- Removed `SparkLine::getPeriod()`
- Removed dependencies on `spatie/period` and `laravel/collection`
- Rename `SparkLineDay` to `SparkLineEntry`
- Allow integers to be passed directly into a new `SparkLine` instead of requiring `SparkLineEntry` objects
