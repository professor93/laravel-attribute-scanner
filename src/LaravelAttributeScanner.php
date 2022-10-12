<?php

namespace Uzbek\LaravelAttributeScanner;

use ReflectionClass;

class LaravelAttributeScanner
{
    public function __construct(private readonly string|array $directories = [])
    {
    }

    /**
     * @throws \Throwable
     */
    public function getAttributes($asArray = false): array
    {
        $attributes = [];
        array_map(function (ReflectionClass $class) use ($asArray, &$attributes) {
            $className = $class->getName();
            foreach ($class->getAttributes() as $key => $attribute) {
                $attrKey = $class->getName().','.$key;
                $attr = new Attribute($attribute, $className);
                $attributes[$attrKey] = $asArray ? $attr->toArray() : $attr;
            }

            foreach ($class->getMethods() as $method) {
                $methodName = $className.'@'.$method->getName();
                foreach ($method->getAttributes() as $attribute) {
                    $attr = new Attribute($attribute, $class, $method);
                    $attributes[$methodName] = $asArray ? $attr->toArray() : $attr;
                }
                foreach ($method->getParameters() as $parameter) {
                    $paramName = $className.'@'.$method->getName().'>'.$parameter->getName();
                    foreach ($parameter->getAttributes() as $attribute) {
                        $attr = new Attribute($attribute, $class, $method, parameter: $parameter);
                        $attributes[$paramName] = $asArray ? $attr->toArray() : $attr;
                    }
                }
            }

            foreach ($class->getProperties() as $property) {
                $propName = $className.'.'.$property->getName();
                foreach ($property->getAttributes() as $attribute) {
                    $attr = new Attribute($attribute, $class, property: $property);
                    $attributes[$propName] = $asArray ? $attr->toArray() : $attr;
                }
            }

            foreach ($class->getConstants() as $constant) {
                $constName = $className.':'.$constant->getName();
                foreach ($constant->getAttributes() as $attribute) {
                    $attr = new Attribute($attribute, $class, constant: $constant);
                    $attributes[$constName] = $asArray ? $attr->toArray() : $attr;
                }
            }
        }, $this->getClasses(true));

        return $attributes;
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
}
