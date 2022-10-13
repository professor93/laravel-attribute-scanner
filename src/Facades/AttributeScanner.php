<?php

namespace Uzbek\LaravelAttributeScanner\Facades;

use Illuminate\Support\Facades\Facade;
use Uzbek\LaravelAttributeScanner\LaravelAttributeScanner;

/**
 * @see \Uzbek\LaravelAttributeScanner\LaravelAttributeScanner
 * @method static array getAttributes(array|string $names = [], bool $asArray = false)
 * @method static array getClasses(bool $asReflection = false)
 * @method static array getDirectories()
 */
class AttributeScanner extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LaravelAttributeScanner::class;
    }
}
