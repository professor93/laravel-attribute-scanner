<?php

namespace Uzbek\LaravelAttributeScanner;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;

class ClassFinder
{
    /**
     * @param string|null $directory
     * @param string|null $parentClass
     * @param string|null $basePath
     * @param string|null $baseNamespace
     * @return Collection<class-string>
     * @throws \Throwable
     */
    public static function all(string $directory = null, string $parentClass = null, string $basePath = null, string $baseNamespace = null, $asReflection = false): Collection
    {
        if (empty($parentClass)) $parentClass = null;
        throw_if($parentClass !== null && !class_exists($parentClass), new Exception("Class {$parentClass} does not exist"));

        $directory ??= app_path();
        $basePath ??= base_path();
        $baseNamespace ??= '';


        $classes = collect(static::getFilesRecursively($directory))
            ->map(fn(string $class) => new SplFileInfo($class))
            ->map(fn(SplFileInfo $file) => self::fullQualifiedClassNameFromFile($file, $basePath, $baseNamespace))
            ->map(fn(string $class) => rescue(fn() => new ReflectionClass($class)))
            ->filter()
            ->filter(fn(ReflectionClass $class) => $parentClass === null || $class->isSubclassOf($parentClass))
            ->filter(fn(ReflectionClass $class) => !$class->isAbstract())
            ->values();

        return $asReflection ? $classes : $classes->map(fn(ReflectionClass $reflectionClass) => $reflectionClass->getName());
    }

    protected static function getFilesRecursively(string $path): array
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $files = [];
        foreach ($rii as $file) {
            if (!$file->isDir()) $files[] = $file->getPathname();
        }
        return $files;
    }

    protected static function fullQualifiedClassNameFromFile(SplFileInfo $file, string $basePath, string $baseNamespace): string
    {
        return Str::of($file->getRealPath())
            ->replaceFirst($basePath, '')
            ->replaceLast('.php', '')
            ->trim(DIRECTORY_SEPARATOR)
            ->ucfirst()
//            ->replace([DIRECTORY_SEPARATOR], ['\\'])
            ->replace([DIRECTORY_SEPARATOR, 'App\\'], ['\\', app()->getNamespace()])
            ->prepend($baseNamespace . '\\');
    }
}
