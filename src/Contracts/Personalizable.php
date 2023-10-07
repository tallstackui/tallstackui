<?php

namespace TallStackUi\Contracts;

interface Personalizable
{
    public function __invoke(array $data): string;
}
