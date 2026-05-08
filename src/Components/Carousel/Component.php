<?php

namespace TallStackUi\Components\Carousel;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('carousel')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public Collection|array|null $images = null,
        public ?int $cover = null,
        public ?bool $autoplay = null,
        public ?int $interval = 3,
        public ?bool $withoutLoop = null,
        public ?bool $withoutIndicators = null,
        public ?bool $stopOnHover = null,
        public ?bool $round = null,
        public ?bool $shuffle = null,
        public ?bool $clickable = null,
        public ?bool $navigable = null,
        public ?string $caption = null,
        public ?string $wrapper = null,
        public ?ComponentSlot $header = null,
        public ?ComponentSlot $footer = null,
    ) {
        $this->images = collect($this->images);

        $this->cover ??= $this->images->where('cover', '=', true)->keys()->values()->first() + 1 ?? 1;

        $this->images = $this->images->toArray();

        $this->interval *= 1000;
    }

    public function blade(): View
    {
        return view('ts-ui::components.carousel.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'relative w-full overflow-hidden',
                'second' => 'relative w-full',
                'fallback-height' => 'min-h-[50svh]',
            ],
            'images' => [
                'wrapper' => [
                    'first' => 'absolute inset-0',
                    'second' => 'lg:px-32 lg:py-14 absolute inset-0 z-10 flex flex-col items-center justify-end gap-2 bg-gradient-to-t from-primary-900/85 dark:from-dark-900/85 to-transparent px-16 py-12 text-center',
                ],
                'content' => [
                    'title' => 'w-full text-balance text-2xl lg:text-3xl font-bold text-white',
                    'description' => 'text-sm text-white',
                ],
                'base' => 'absolute w-full h-full inset-0 object-cover text-slate-700 dark:text-slate-300',
                'rounded' => 'rounded-xl',
            ],
            'buttons' => [
                'left' => [
                    'base' => 'cursor-pointer absolute left-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-white/40 p-2 text-slate-700 transition hover:bg-white/60 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 active:outline-offset-0 dark:bg-dark-900/40 dark:text-dark-300 dark:hover:bg-dark-900/60 dark:focus-visible:outline-blue-600',
                    'icon.size' => 'w-6 h-6 pr-0.5',
                ],
                'right' => [
                    'base' => 'cursor-pointer absolute right-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-white/40 p-2 text-slate-700 transition hover:bg-white/60 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 active:outline-offset-0 dark:bg-dark-900/40 dark:text-dark-300 dark:hover:bg-dark-900/60 dark:focus-visible:outline-blue-600',
                    'icon.size' => 'w-6 h-6 pl-0.5',
                ],
            ],
            'indicators' => [
                'wrapper' => 'absolute rounded-xl bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 bg-white/75 px-1.5 py-1 md:px-2 dark:bg-dark-900/75',
                'buttons' => [
                    'base' => 'w-2 h-2 cursor-pointer rounded-full transition bg-dark-700 dark:bg-dark-300',
                    'current' => 'bg-dark-700 dark:bg-dark-300',
                    'inactive' => 'bg-dark-700/50 dark:bg-dark-300/50',
                ],
            ],
            'clickable' => [
                'trigger' => 'block h-full w-full cursor-zoom-in',
                'overlay' => 'fixed inset-0 z-50 flex items-center justify-center bg-black/85 p-4 sm:p-8',
                'image' => 'max-h-full max-w-full object-contain',
                'close' => [
                    'button' => 'absolute right-4 top-4 z-10 inline-flex cursor-pointer items-center justify-center text-white transition hover:opacity-80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white',
                    'icon' => 'h-6 w-6',
                ],
                'navigable' => [
                    'button' => [
                        'left' => [
                            'base' => 'absolute left-4 sm:left-6 top-1/2 z-10 inline-flex -translate-y-1/2 cursor-pointer items-center justify-center rounded-full bg-white/20 p-2 text-white backdrop-blur-sm transition hover:bg-white/35 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white disabled:cursor-not-allowed disabled:opacity-40',
                            'icon.size' => 'h-6 w-6 pr-0.5',
                        ],
                        'right' => [
                            'base' => 'absolute right-4 sm:right-6 top-1/2 z-10 inline-flex -translate-y-1/2 cursor-pointer items-center justify-center rounded-full bg-white/20 p-2 text-white backdrop-blur-sm transition hover:bg-white/35 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white disabled:cursor-not-allowed disabled:opacity-40',
                            'icon.size' => 'h-6 w-6 pl-0.5',
                        ],
                    ],
                ],
                'caption' => [
                    'overlay' => [
                        'figure' => 'relative w-fit max-h-full max-w-full',
                        'image' => 'block max-h-[calc(100dvh-2rem)] max-w-[calc(100dvw-2rem)] sm:max-h-[calc(100dvh-4rem)] sm:max-w-[calc(100dvw-4rem)] object-contain',
                        'wrapper' => 'absolute inset-x-0 bottom-0 z-10 bg-gradient-to-t from-black/85 via-black/55 to-transparent px-6 py-6 sm:px-10 sm:py-8 text-center',
                        'title' => 'text-balance text-xl sm:text-2xl font-semibold text-white',
                        'description' => 'mt-1.5 text-sm text-white/85 max-w-3xl mx-auto',
                    ],
                    'footer' => [
                        'figure' => 'flex max-h-full max-w-full flex-col items-center gap-4',
                        'image' => 'min-h-0 max-w-full flex-1 object-contain',
                        'wrapper' => 'shrink-0 max-w-3xl text-center',
                        'title' => 'text-balance text-xl sm:text-2xl font-semibold text-white',
                        'description' => 'mt-1.5 text-sm text-white/75',
                    ],
                ],
            ],
        ]);
    }

    protected function validate(): void
    {
        if (blank($this->images)) {
            __ts_validation_exception($this, 'The [images] attribute is required.');
        }

        if ($this->caption !== null && ! in_array($this->caption, ['overlay', 'footer'], true)) {
            __ts_validation_exception($this, 'The [caption] must be one of: overlay, footer.');
        }

        if ($this->caption !== null && ! $this->clickable) {
            __ts_validation_exception($this, 'The [caption] requires [clickable] to be enabled.');
        }

        if ($this->navigable && ! $this->clickable) {
            __ts_validation_exception($this, 'The [navigable] requires [clickable] to be enabled.');
        }
    }
}
