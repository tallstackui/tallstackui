<?php

namespace TasteUi\Contracts;

interface Customizable
{
    public function customization(): array;

    public function tasteUiClasses(): array;
}
