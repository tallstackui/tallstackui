<?php

namespace TasteUi\Contracts;

interface Personalizable
{
    public function __invoke(array $data): string;
}
