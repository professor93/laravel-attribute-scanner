<?php

namespace Uzbek\LaravelAttributeScanner;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAttributeScannerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         */
        $package->name('laravel-attribute-scanner')->hasConfigFile();

        $this->app->singleton(LaravelAttributeScanner::class, fn() => new LaravelAttributeScanner(config('attribute-scanner.directories', ['app'])));
    }
}
