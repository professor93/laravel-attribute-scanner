<?php

namespace Uzbek\LaravelAttributeScanner\Facades;

use Illuminate\Support\Facades\Facade;
use Uzbek\LaravelAttributeScanner\LaravelAttributeScanner;

/**
 * @see \Uzbek\LaravelAttributeScanner\LaravelAttributeScanner
 */
class AttributeScanner extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LaravelAttributeScanner::class;
    }
}
