<?php

namespace TasteUi\Contracts;

interface Customizable
{
    public function customize(bool $error = false): array;
}
