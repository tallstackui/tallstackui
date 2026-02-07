<?php

namespace TallStackUi\Support\Miscellaneous;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use TallStackUi\TallStackUiComponent;

class ReflectComponent
{
    /** @var array<string, array{reflection?: ReflectionClass, parent?: ReflectionClass}> */
    private static array $cache = [];

    public function __construct(private readonly string $component)
    {
        //
    }

    /**
     * Get the SoftPersonalization attribute instance.
     *
     * @throws ReflectionException
     */
    public function attribute(string $attribute): ?ReflectionAttribute
    {
        $key = $this->component.'|'.$attribute;

        return self::$cache[$key] ??= ($this->parent()->getAttributes($attribute)[0] ?? null);
    }

    /**
     * Get the ReflectionClass instance.
     *
     * @throws ReflectionException
     */
    public function class(): ReflectionClass
    {
        return $this->reflection();
    }

    /**
     * Determines and gets the parent component.
     *
     * @throws ReflectionException
     */
    public function parent(): bool|ReflectionClass
    {
        if (isset(self::$cache[$this->component]['parent'])) {
            return self::$cache[$this->component]['parent'];
        }

        $class = $this->reflection();
        $parent = $class->getParentClass();

        // If the parent isn't the BaseComponent, then a deep personalization is happening.
        return self::$cache[$this->component]['parent'] = $parent->name !== TallStackUiComponent::class ? $parent : $class;
    }

    /**
     * Get the ReflectionClass instance, cached per component class.
     *
     * @throws ReflectionException
     */
    private function reflection(): ReflectionClass
    {
        return self::$cache[$this->component]['reflection'] ??= new ReflectionClass($this->component);
    }
}
