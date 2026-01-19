<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('carousel')]
class Carousel extends TallStackUiComponent implements Personalization
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
        return view('tallstack-ui::components.carousel');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'relative w-full overflow-hidden',
                'second' => 'relative w-full',
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
        ]);
    }

    protected function validate(): void
    {
        if (blank($this->images)) {
            __ts_validation_exception($this, 'The [images] attribute is required.');
        }
    }
}
