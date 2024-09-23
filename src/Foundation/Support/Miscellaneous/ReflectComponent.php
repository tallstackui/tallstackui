<?php

namespace TallStackUi\Foundation\Support\Miscellaneous;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\TallStackUiComponent;

class ReflectComponent
{
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
        return collect($this->parent()->getAttributes($attribute))->first();
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
        $class = new ReflectionClass($this->component);
        $parent = $class->getParentClass();

        // If the parent isn't the BaseComponent, then a deep personalization is happening.
        return $parent->name !== TallStackUiComponent::class ? $parent : $class;
    }

    /**
     * Get a new ReflectionClass instance.
     *
     * @throws ReflectionException
     */
    private function reflection(): ReflectionClass
    {
        return new ReflectionClass($this->component);
    }
}
