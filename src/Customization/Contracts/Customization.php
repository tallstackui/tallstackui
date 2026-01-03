<?php

namespace TallStackUi\Customization\Contracts;

interface Customization
{
    /**
     * Get the customization of the component, blocks, and CSS classes.
     */
    public function customization(): array;
}
