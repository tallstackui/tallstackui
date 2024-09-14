<?php

namespace TallStackUi\Foundation\Personalization\Contracts;

interface Personalization
{
    /**
     * Get the personalization of the component, blocks and CSS classes.
     */
    public function personalization(): array;
}
