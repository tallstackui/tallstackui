<?php

namespace TallStackUi\Components\Layout\SideBar\Main;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('sideBar')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ComponentSlot|string|null $brand = null,
        public ComponentSlot|string|null $brandCollapsed = null,
        public ComponentSlot|string|null $footer = null,
        public ?bool $smart = null,
        public ?bool $navigate = null,
        public ?bool $navigateHover = null,
        public ?bool $thinScroll = null,
        public ?bool $thickScroll = null,
        public ?bool $collapsible = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.layout.sidebar.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'mobile' => [
                'wrapper' => [
                    'first' => 'relative z-50 md:hidden',
                    'second' => 'fixed inset-0 flex',
                    'third' => 'relative mr-16 flex w-full max-w-xs flex-1',
                    'fourth' => 'dark:bg-dark-700 flex grow flex-col bg-white pb-4',
                    'fifth' => 'flex h-16 flex-1 flex-col',
                    'sixth' => 'flex flex-1 flex-col gap-y-0.5 px-2',
                    'brand.margin' => 'mt-10',
                    'items' => 'flex-1 min-h-0 overflow-y-auto overflow-x-hidden gap-y-5',
                ],
                'backdrop' => 'fixed inset-0 bg-gray-900/80 dark:bg-dark-900/50',
                'button' => [
                    'wrapper' => 'absolute left-full top-0 flex w-16 justify-center pt-5',
                    'size' => 'w-6 h-6 text-white',
                    'icon' => 'x-mark',
                ],
                'footer' => 'shrink-0 border-t border-gray-200 dark:border-dark-600 px-2 py-4',
                'scrollbar' => [
                    'thin' => 'soft-scrollbar',
                    'thick' => 'custom-scrollbar',
                ],
            ],
            'desktop' => [
                'wrapper' => [
                    'first' => [
                        'base' => 'hidden md:fixed md:inset-y-0 md:z-40 md:flex md:flex-col',
                        'size' => 'md:w-72',
                    ],
                    'second' => 'dark:bg-dark-700 dark:border-dark-600 flex grow flex-col border-r border-gray-200 bg-white pb-4 transition-[width] duration-300',
                    'third' => 'flex h-16 shrink-0 items-center',
                    'brand.margin' => 'mt-10',
                    'fourth' => 'flex h-16 flex-1 flex-col',
                    'fifth' => 'flex flex-1 flex-col gap-y-0.5 px-2',
                    'items' => 'flex-1 min-h-0 overflow-y-auto overflow-x-hidden gap-y-5',
                ],
                'sizes' => [
                    'expanded' => 'w-72',
                    'collapsed' => 'w-22',
                ],
                'footer' => 'shrink-0 overflow-hidden border-t border-gray-200 dark:border-dark-600 px-2 pt-3',
                'scrollbar' => [
                    'thin' => 'soft-scrollbar',
                    'thick' => 'custom-scrollbar',
                ],
            ],
        ]);
    }
}
