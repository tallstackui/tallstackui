<?php

namespace TallStackUi\View\Components;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use TallStackUi\Foundation\Attributes\ColorsThroughOf;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Colors\Components\BannerColors;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('banner')]
#[ColorsThroughOf(BannerColors::class)]
class Banner extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public string|array|Collection|null $text = null,
        public string|null|array $color = 'primary',
        public ?bool $close = false,
        public ?bool $animated = false,
        public ?int $enter = 3,
        public ?int $leave = null,
        public string|null|Carbon $until = null,
        public ?bool $wire = false,
        public ?bool $light = false,
        public ?bool $show = true,
        public ?string $size = 'sm',
        #[SkipDebug]
        public ?string $left = null,
        #[SkipDebug]
        public ?string $style = 'solid',
    ) {
        $this->style = $this->light ? 'light' : $this->style;
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.banner');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wire' => 'sticky top-0 z-50',
            'wrapper' => 'relative flex flex-row items-center justify-between px-6 py-2',
            'sizes' => [
                'sm' => 'py-2',
                'md' => 'py-3',
                'lg' => 'py-4',
            ],
            'slot.left' => 'absolute left-0 ml-4 text-sm font-medium',
            'text' => 'grow text-center text-sm font-medium',
            'icon' => 'w-5 h-5 text-white',
            'close' => 'h-4 w-4 cursor-pointer',
        ]);
    }

    protected function setup(): void
    {
        // If the banner is wire, we don't need to set up anything else
        // Because the banner will be displayed through the Livewire events
        if ($this->wire) {
            return;
        }

        if ($this->text instanceof Collection) {
            $this->text = $this->text->values()
                ->toArray();
        }

        if (is_array($this->text)) {
            $this->text = $this->text[array_rand($this->text)];
        }

        if (is_null($this->until)) {
            return;
        }

        if (today()->lessThanOrEqualTo(Carbon::parse($this->until))) {
            return;
        }

        $this->show = false;
    }

    protected function validate(): void
    {
        $sizes = ['sm', 'md', 'lg'];

        if (! in_array($this->size, $sizes)) {
            __ts_validation_exception($this, 'The [size] must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (is_array($this->color)) {
            if (! isset($this->color['background'])) {
                __ts_validation_exception($this, 'The [background] key must exists when color is an array.');
            }

            if (! isset($this->color['text'])) {
                __ts_validation_exception($this, 'The [color] key must exists when color is an array.');
            }
        }

        // If the banner is wire, we don't need to validate the until property
        // Because the banner will be displayed through the Livewire events
        if (is_null($this->until) || $this->wire) {
            return;
        }

        $until = null;

        try {
            $until = Carbon::parse($this->until);
        } catch (Exception) {
            //
        }

        if (blank($until)) {
            __ts_validation_exception($this, 'The [until] attribute must be a Carbon instance or a valid date string.');
        }
    }
}
