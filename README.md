# Simple yet highly extensible package for A/B testing in PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graymatterlabs/experiment.svg?style=flat-square)](https://packagist.org/packages/graymatterlabs/experiment)
[![Tests](https://github.com/graymatterlabs/experiment/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/graymatterlabs/experiment/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/graymatterlabs/experiment.svg?style=flat-square)](https://packagist.org/packages/graymatterlabs/experiment)

This package makes no opinions on how you store your experiment data. Bring your own implementation of `GrayMatterLabs\Experiment\Contracts\Persistence` or use one of the provided implementations.

How you choose to implement your `GrayMatterLabs\Experiment\Contracts\Factory` will determine how you reference your experiments throughout the code. By default, there is an implementation provided that references experiments by their fully qualified class names.

Similarly, you may choose to and are encouraged to configure your experiments in a dynamic and/or data-backed way by instantiating them with data from any number of storage mechanisms as well as in any shape you see fit.

In general, this package attempts to not strictly enforce opinions as much as possible but rather provide an open-ended framework for A/B testing that minimizes complexity and overhead. You are encouraged to bring your own concrete implementations of the provided interfaces. You can, however, lean into the implementations provided for you.

## Installation

You can install the package via composer:

```bash
composer require graymatterlabs/experiment:^0.3
```

## Usage

```php
$manager = new Manager($persistence, $factory, $strategy);
$sample = new Sample();

$allocation = $manager->allocate($experiment, $sample);

if (! $allocation->isAllocated()) {
    // unable to allocate
}

if ($allocation->isVariant('TEST')) {
    // execute experimental feature
}

if ($allocation->wasRecentlyAllocated) {
    // the allocation is new
}
```
> Note: Usage of `GrayMatterLabs\Experiment\Persistence\SimpleCachePersistence` requires you satisfy the `Psr\SimpleCache\CacheInterface` interface. See graymatterlabs/simple-cache or bring your own implementation.

## Testing

```bash
composer test
```

## Changelog

Please see the [Release Notes](../../releases) for more information on what has changed recently.

## Credits

- [Ryan Colson](https://github.com/ryancco)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
