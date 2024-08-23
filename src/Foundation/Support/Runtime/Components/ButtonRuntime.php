<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

class ButtonRuntime
{
    public function runtime(array $data): array
    {
        return [
            'tag' => filled($data['href']) ? 'a' : 'button',
        ];
    }
}
