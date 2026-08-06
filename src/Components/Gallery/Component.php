<?php

namespace TallStackUi\Components\Gallery;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\GalleryRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('gallery')]
#[PassThroughRuntime(GalleryRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public Collection|array|null $images = null,
        public ?bool $grid = null,
        public ?bool $masonry = null,
        public ?bool $feature = null,
        public ?int $columns = null,
        public ?string $ratio = null,
        public ?int $limit = null,
        public ?string $thumbnails = null,
        public ?string $height = null,
        public ?bool $clickable = null,
        public ?bool $navigable = null,
        public ?string $caption = null,
        public ?bool $withoutLoop = null,
        public ?bool $round = null,
        public ComponentSlot|string|null $header = null,
        public ComponentSlot|string|null $footer = null,
    ) {
        $this->images = collect($this->images)->values()->toArray();
    }

    public function blade(): View
    {
        return view('ts-ui::components.gallery.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'relative w-full',
            'scroll' => 'custom-scrollbar overflow-y-auto',
            'height' => [
                '40' => 'max-h-40',
                '60' => 'max-h-60',
                '80' => 'max-h-80',
                '96' => 'max-h-96',
            ],
            'grid' => [
                'wrapper' => 'grid',
                'gap' => 'gap-2',
            ],
            'masonry' => [
                'wrapper' => 'w-full',
                'gap' => 'gap-2',
                'tile' => 'mb-2 break-inside-avoid',
            ],
            'feature' => [
                'wrapper' => [
                    'bottom' => 'flex flex-col gap-2',
                    'left' => 'relative flex flex-col gap-2 sm:block',
                    'right' => 'relative flex flex-col gap-2 sm:block',
                ],
                'cover' => [
                    'wrapper' => [
                        'bottom' => 'w-full min-w-0',
                        'left' => 'w-full min-w-0 sm:ml-26 sm:w-auto',
                        'right' => 'w-full min-w-0 sm:mr-26 sm:w-auto',
                    ],
                ],
                'thumbnails' => [
                    'wrapper' => [
                        'bottom' => 'grid grid-cols-3 gap-2 sm:grid-cols-6',
                        'left' => 'custom-scrollbar grid grid-cols-4 gap-2 sm:absolute sm:inset-y-0 sm:left-0 sm:w-24 sm:auto-rows-min sm:grid-cols-1 sm:overflow-y-auto',
                        'right' => 'custom-scrollbar grid grid-cols-4 gap-2 sm:absolute sm:inset-y-0 sm:right-0 sm:w-24 sm:auto-rows-min sm:grid-cols-1 sm:overflow-y-auto',
                    ],
                    'ratio' => 'aspect-square',
                ],
                'remaining' => [
                    'wrapper' => 'absolute inset-0 z-10 flex items-center justify-center bg-black/60 transition',
                    'text' => 'text-lg font-semibold text-white sm:text-xl',
                ],
            ],
            'tile' => [
                'base' => 'relative overflow-hidden',
                'performance' => '[content-visibility:auto] [contain-intrinsic-size:auto_300px]',
                'trigger' => 'block h-full w-full cursor-zoom-in',
                'link' => 'block h-full w-full',
                'image' => 'h-full w-full object-cover text-slate-700 dark:text-dark-300',
                'rounded' => 'rounded-lg',
            ],
            'lightbox' => [
                'overlay' => 'fixed inset-0 z-50 flex items-center justify-center bg-black/90 dark:bg-dark-800/90 p-4 sm:p-8',
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

        if (collect([$this->grid, $this->masonry, $this->feature])->filter()->count() > 1) {
            __ts_validation_exception($this, 'The [grid], [masonry] and [feature] cannot be used together.');
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

        if ($this->withoutLoop && ! $this->clickable) {
            __ts_validation_exception($this, 'The [without-loop] requires [clickable] to be enabled.');
        }

        if ($this->columns !== null && ($this->columns < 2 || $this->columns > 6)) {
            __ts_validation_exception($this, 'The [columns] must be between 2 and 6.');
        }

        if ($this->ratio !== null && ! in_array($this->ratio, ['square', 'video', 'portrait'], true)) {
            __ts_validation_exception($this, 'The [ratio] must be one of: square, video, portrait.');
        }

        if ($this->limit !== null && $this->limit < 2) {
            __ts_validation_exception($this, 'The [limit] must be at least 2.');
        }

        if ($this->thumbnails !== null && ! in_array($this->thumbnails, ['bottom', 'left', 'right'], true)) {
            __ts_validation_exception($this, 'The [thumbnails] must be one of: bottom, left, right.');
        }

        if ($this->thumbnails !== null && ! $this->feature) {
            __ts_validation_exception($this, 'The [thumbnails] can only be used with [feature].');
        }

        $heights = ['40', '60', '80', '96'];

        if ($this->height !== null && ! in_array($this->height, $heights, true)) {
            __ts_validation_exception($this, 'The [height] must be one of: ['.implode(', ', $heights).'].');
        }

        if ($this->columns !== null && $this->feature) {
            __ts_validation_exception($this, 'The [columns] cannot be used with [feature].');
        }

        if ($this->limit !== null && ! $this->feature) {
            __ts_validation_exception($this, 'The [limit] can only be used with [feature].');
        }

        if ($this->ratio !== null && $this->masonry) {
            __ts_validation_exception($this, 'The [ratio] cannot be used with [masonry].');
        }
    }
}
