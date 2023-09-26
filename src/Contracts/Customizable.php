<?php

namespace TasteUi\Contracts;

interface Customizable
{
    public function customization(bool $error = false): array;
}
