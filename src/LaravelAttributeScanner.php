<?php

namespace Uzbek\LaravelAttributeScanner;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionExtension;
use ReflectionZendExtension;
use Reflector;

class LaravelAttributeScanner
{
    public function __construct(private readonly string|array $directories = [])
    {
    }

    /**
     * @throws \Exception
     */
    private function scanAttributes(Reflector $reflector, ReflectionClass $class, ?string $name = null, bool $asArray = false): array
    {
        if ($reflector instanceof ReflectionExtension || $reflector instanceof ReflectionZendExtension || $reflector instanceof ReflectionAttribute) {
            return [];
        }

        if (is_string($name) && strlen($name) === 0) {
            $name = null;
        }

        $items = [];
        foreach ($reflector->getAttributes($name) as $attribute) {
            $attr = new Attribute($attribute, $class);
            $items[] = $asArray ? $attr->toArray() : $attr;
        }

        return $items;
    }

    /**
     * @param class-string<T>[]|string|null $names Name of an attribute class
     * @param  bool  $asArray Result as array
     *
     * @throws \Throwable
     */
    public function getAttributes(array|string $names = [], bool $asArray = false): array
    {
        if (is_string($names)) {
            $names = [$names];
        } elseif (! is_array($names) || count($names) === 0) {
            $names = [null];
        }

        $attributes = [];
        /** @var \ReflectionClass $class */
        foreach ($this->getClasses(asReflection: true) as $class) {
            $className = $class->getName();

            foreach ($names as $name) {
                $this->addOrMergeArrayItem($attributes, $className, $this->scanAttributes($class, $class, $name, $asArray));
                foreach ($class->getMethods() as $method) {
                    $methodName = $className.'@'.$method->getName();
                    $this->addOrMergeArrayItem($attributes, $methodName, $this->scanAttributes($method, $class, $name, $asArray));
                    foreach ($method->getParameters() as $parameter) {
                        $paramName = $className.'@'.$method->getName().'>'.$parameter->getName();
                        $this->addOrMergeArrayItem($attributes, $paramName, $this->scanAttributes($parameter, $class, $name, $asArray));
                    }
                }
                foreach ($class->getProperties() as $property) {
                    $propName = $className.'.'.$property->getName();
                    $this->addOrMergeArrayItem($attributes, $propName, $this->scanAttributes($property, $class, $name, $asArray));
                }
                foreach ($class->getReflectionConstants() as $constant) {
                    $constName = $className.':'.$constant->getName();
                    $this->addOrMergeArrayItem($attributes, $constName, $this->scanAttributes($constant, $class, $name, $asArray));
                }
            }
        }

        return array_filter($attributes);
    }

    /**
     * @throws \Throwable
     */
    public function getClasses($asReflection = false): array
    {
        $classes = collect();
        foreach ($this->getDirectories() as $directory) {
            $classes = $classes->merge(ClassFinder::all($directory, asReflection: $asReflection));
        }

        return $classes->unique()->toArray();
    }

    /**
     * @return array
     */
    public function getDirectories(): array
    {
        return is_null($this->directories) ? [] : (is_array($this->directories) ? $this->directories : [$this->directories]);
    }

    private function addOrMergeArrayItem(array &$array, $key, array $value): void
    {
        if (array_key_exists($key, $array) && ! empty($array[$key])) {
            $array[$key] = array_merge($array[$key], $value);
        } else {
            $array[$key] = $value;
        }
    }
}
