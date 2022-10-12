<?php

namespace Uzbek\LaravelAttributeScanner;

use Exception;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

class Attribute
{
    public string $target = '';

    public function __construct(
        public readonly ReflectionAttribute      $attribute,
        public readonly ?ReflectionClass         $class = null,
        public readonly ?ReflectionMethod        $method = null,
        public readonly ?ReflectionProperty      $property = null,
        public readonly ?ReflectionClassConstant $constant = null,
        public readonly ?ReflectionParameter     $parameter = null,
    )
    {
        $this->target = match ($this->attribute->getTarget()) {
            \Attribute::TARGET_CLASS => 'class',
            \Attribute::TARGET_METHOD => 'method',
            \Attribute::TARGET_PROPERTY => 'property',
            \Attribute::TARGET_CLASS_CONSTANT => 'constant',
            \Attribute::TARGET_PARAMETER => 'parameter',
            \Attribute::TARGET_ALL => 'all',
            default => throw new Exception('Unknown target'),
        };
    }

    public function toArray(): array
    {
        return array_filter([
            'class' => $this->class?->getName(),
            'method' => $this->method?->getName(),
            'property' => $this->property?->getName(),
            'constant' => $this->constant?->getName(),
            'parameter' => $this->parameter?->getName(),
            'target' => $this->target,
            'name' => $this->attribute->getName(),
            'arguments' => $this->attribute->getArguments(),
        ]);
    }
}
