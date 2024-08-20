<?php

use Illuminate\Support\Arr;

test('contains methods', function (string $component, string|array $methods) {
    $collect = __ts_class_collection($component);
    $content = file_get_contents($collect->get('stub'));

    collect(Arr::wrap($methods))
        ->each(function (string $method) use ($content): void {
            $content .= 'Colors(Component $component): array';

            expect($content)->toContain($method);
        });
})->with([
    ['Alert', ['background', 'text']],
    ['Avatar', 'background'],
    ['Badge', ['background', 'icon', 'text']],
    ['Banner', ['background', 'text']],
    ['Boolean', ['icon']],
    ['Button', ['background', 'icon']],
    ['Dialog', ['cancel', 'confirm', 'icon']],
    ['Errors', ['background', 'border', 'text']],
    ['Link', ['text']],
    ['Progress', 'background'],
    ['Radio', 'background'],
    ['Range', 'thumb'],
    ['Rating', 'background'],
    ['Stats', 'background'],
    ['Toast', 'icon'],
    ['Toggle', 'background'],
    ['Tooltip', 'icon'],
]);
