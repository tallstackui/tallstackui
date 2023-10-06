<?php

namespace TallStackUi\Contracts;

interface Customizable
{
    public function customization(): array;

    public function tasteUiClasses(): array;
}
