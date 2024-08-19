<?php

namespace TallStackUi\Foundation\Support\Components;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\View\Components\BaseComponent;

class ReflectParentComponent
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
    public function attribute(): ReflectionAttribute
    {
        return collect($this->parent()->getAttributes(SoftPersonalization::class))->first();
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
        return $parent->name !== BaseComponent::class ? $parent : $class;
    }
}
