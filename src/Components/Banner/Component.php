<?php

namespace TallStackUi\Components\Banner;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\BannerColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('banner')]
#[ColorsThroughOf(BannerColors::class)]
class Component extends TallStackUiComponent implements Customization
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
        public bool|string $rotate = false,
        public ?string $separator = ' • ',
        #[SkipDebug]
        public ?string $left = null,
        #[SkipDebug]
        public ?string $style = 'solid',
        #[SkipDebug]
        public ?string $speed = null,
    ) {
        $this->style = $this->light ? 'light' : $this->style;
        $this->speed = $this->pick();
    }

    public function blade(): View
    {
        return view('ts-ui::components.banner.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wire' => [
                'base' => 'sticky top-0 z-50',
                'content' => 'flex justify-center items-center gap-2',
                'background' => [
                    'success' => 'bg-green-600',
                    'error' => 'bg-red-600',
                    'warning' => 'bg-yellow-600',
                    'info' => 'bg-blue-600',
                ],
                'text' => [
                    'base' => 'text-white',
                    'success' => 'text-green-50',
                    'error' => 'text-red-50',
                    'warning' => 'text-yellow-50',
                    'info' => 'text-blue-50',
                ],
            ],
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
            'rotate' => [
                'viewport' => 'flex-1 min-w-0 overflow-hidden [container-type:inline-size] [container-name:tsui-banner-rotate] [mask-image:linear-gradient(to_right,transparent,black_8%,black_92%,transparent)]',
                'track' => 'inline-block w-max hover:[animation-play-state:paused]',
                'item' => 'whitespace-nowrap text-sm font-medium',
                'spacing' => [
                    'left' => 'ml-12',
                    'right' => 'mr-8',
                ],
                'speeds' => [
                    'slow' => 'motion-safe:animate-banner-rotate-slow',
                    'normal' => 'motion-safe:animate-banner-rotate-normal',
                    'fast' => 'motion-safe:animate-banner-rotate-fast',
                ],
            ],
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
            $this->text = $this->rotate !== false
                ? implode($this->separator, $this->text)
                : $this->text[array_rand($this->text)];
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

        if ($this->rotate !== false && $this->wire) {
            __ts_validation_exception($this, 'The [rotate] cannot be used together with [wire] mode.');
        }

        if ($this->rotate !== false && $this->rotate !== true && ! in_array($this->rotate, ['slow', 'normal', 'fast'], true)) {
            __ts_validation_exception($this, 'The [rotate] must be one of [slow, normal, fast].');
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

    private function pick(): ?string
    {
        if ($this->rotate === false) {
            return null;
        }

        if ($this->rotate === true) {
            return 'normal';
        }

        return in_array($this->rotate, ['slow', 'normal', 'fast'], true) ? $this->rotate : null;
    }
}
