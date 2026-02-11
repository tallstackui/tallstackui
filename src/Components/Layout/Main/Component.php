<?php

namespace TallStackUi\Components\Layout\Main;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('layout')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ComponentSlot|string|null $top = null,
        public ComponentSlot|string|null $header = null,
        public ComponentSlot|string|null $menu = null,
        public ComponentSlot|string|null $footer = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.layout.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'min-h-full',
                'second' => [
                    'expanded' => 'md:pl-72 transition-[padding] duration-300',
                    'collapsed' => 'md:pl-22 transition-[padding] duration-300',
                ],
            ],
            'main' => 'mx-auto max-w-full p-10',
        ]);
    }
}
