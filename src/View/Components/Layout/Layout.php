<?php

namespace TallStackUi\View\Components\Layout;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('layout')]
class Layout extends TallStackUiComponent implements Personalization
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
        return view('tallstack-ui::components.layout.layout');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'min-h-full',
                'second' => [
                    'expanded' => 'md:pl-72',
                    'collapsed' => 'md:pl-22',
                ],
            ],
            'main' => 'mx-auto max-w-full p-10',
        ]);
    }
}
