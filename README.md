# PHP8 Attribute scanner for Laravel project

[![Latest Version on Packagist](https://img.shields.io/packagist/v/uzbek/laravel-attribute-scanner.svg?style=flat-square)](https://packagist.org/packages/uzbek/laravel-attribute-scanner)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/professor93/laravel-attribute-scanner/run-tests?label=tests)](https://github.com/professor93/laravel-attribute-scanner/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/professor93/laravel-attribute-scanner/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/professor93/laravel-attribute-scanner/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/uzbek/laravel-attribute-scanner.svg?style=flat-square)](https://packagist.org/packages/uzbek/laravel-attribute-scanner)

PHP8 Attribute scanner for Laravel project

## Installation

You can install the package via composer:

```bash
composer require uzbek/laravel-attribute-scanner
```

[//]: # (You can publish the config file with:)

[//]: # (```bash)
[//]: # (php artisan vendor:publish --tag="laravel-attribute-scanner-config")
[//]: # (```)

[//]: # (This is the contents of the published config file:)

[//]: # (```php)
[//]: # (return [)
[//]: # (];)
[//]: # (```)

## Usage

```php
use Uzbek\LaravelAttributeScanner\Facades\AttributeScanner;

$attributes = AttributeScanner::getAttributes(asArray: true);

// or

use Uzbek\LaravelAttributeScanner\LaravelAttributeScanner;

$scanner = new LaravelAttributeScanner(directories: ['app/Models', 'app/Http/Controllers']);
$attributes = $scanner->getAttributes();
```

## Result examples
### asArray = false (default)
```php
[
    "App\Http\Controllers\UserController@create" => {...}, #Uzbek\LaravelAttributeScanner\Attribute, method
    "App\Http\Controllers\UserController@update>id" => {...}, #Uzbek\LaravelAttributeScanner\Attribute, parameter
    "App\Http\Controllers\UserController.service" => {...}, #Uzbek\LaravelAttributeScanner\Attribute, property
]
```
### asArray = true
```php
[
    "App\Http\Controllers\UserController@create" => [
        "class" => "App\Http\Controllers\UserController",
        "method" => "create",
        "target" => "method",
        "name" => "Uzbek\LaravelValidationAttributes\Attributes\Validators",
        "arguments" => [
            [
                "name" => "required|string",
                "email" => "required|email",
                ...
            ],
        ],
    ],
    "App\Http\Controllers\UserController@update>id" => [
        "class" => "App\Http\Controllers\UserController",
        "method" => "update",
        "target" => "parameter",
        "parameter" => "id",
        "name" => "Uzbek\SomeAttributes\ID",
        "arguments" => [],
    ],
    "App\Http\Controllers\UserController.service" => [
        "class" => "App\Http\Controllers\UserController",
        "target" => "property",
        "property" => "id",
        "name" => "Uzbek\SomeAttributes\ID",
        "arguments" => [],
    ],
    "App\Http\Controllers\UserController:IS_PUBLIC" => [
        "class" => "App\Http\Controllers\UserController",
        "target" => "constant",
        "constant" => "IS_PUBLIC",
        "name" => "Uzbek\SomeAttributes\Casts\Integer",
        "arguments" => [],
    ],
    ...
]
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Inoyatulloh](https://github.com/professor93)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
