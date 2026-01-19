<?php

namespace TallStackUi\View\Components\Layout\SideBar;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('sideBar')]
class SideBar extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ComponentSlot|string|null $brand = null,
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
        return view('tallstack-ui::components.layout.sidebar.sidebar');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'mobile' => [
                'wrapper' => [
                    'first' => 'relative z-50 md:hidden',
                    'second' => 'fixed inset-0 flex',
                    'third' => 'relative mr-16 flex w-full max-w-xs flex-1',
                    'fourth' => 'dark:bg-dark-700 flex grow flex-col gap-y-5 overflow-y-auto overflow-x-hidden bg-white px-2 pb-4',
                    'fifth' => 'flex h-16 shrink-0 items-center',
                    'brand.margin' => 'mt-10',
                    'sixth' => 'flex h-16 flex-1 flex-col',
                    'seventh' => 'flex flex-1 flex-col gap-y-0.5',
                ],
                'backdrop' => 'fixed inset-0 bg-gray-900/80 dark:bg-dark-900/50',
                'button' => [
                    'wrapper' => 'absolute left-full top-0 flex w-16 justify-center pt-5',
                    'size' => 'w-6 h-6 text-white',
                    'icon' => 'x-mark',
                ],
            ],
            'desktop' => [
                'wrapper' => [
                    'first' => [
                        'base' => 'hidden md:fixed md:inset-y-0 md:z-40 md:flex md:flex-col',
                        'size' => 'md:w-72',
                    ],
                    'second' => 'dark:bg-dark-700 dark:border-dark-600 flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-2 pb-4',
                    'third' => 'flex h-16 shrink-0 items-center',
                    'brand.margin' => 'mt-10',
                    'fourth' => 'flex h-16 flex-1 flex-col',
                    'fifth' => 'flex flex-1 flex-col gap-y-0.5',
                ],
                'sizes' => [
                    'expanded' => 'w-72',
                    'collapsed' => 'w-22',
                ],
                'collapse' => [
                    'wrapper' => 'absolute top-2 left-5 z-50',
                    'buttons' => [
                        'expanded' => [
                            'icon' => 'chevron-left',
                            'class' => 'w-5 h-5 text-primary-500 dark:text-dark-300',
                        ],
                        'collapsed' => [
                            'icon' => 'chevron-right',
                            'class' => 'w-5 h-5 text-primary-500 dark:text-dark-300',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
