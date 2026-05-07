<?php

namespace TallStackUi\Components\Card;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\CardColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('card')]
#[ColorsThroughOf(CardColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $color = null,
        public ?bool $light = null,
        public ?bool $bordered = null,
        public ?string $minimize = null,
        public ?bool $close = null,
        public string|bool|null $loading = null,
        public ?string $delay = null,
        public ?string $image = null,
        public ?string $position = 'top',
        public bool|string|null $round = false,
        #[SkipDebug]
        public ?bool $initializeMinimized = false,
        #[SkipDebug]
        public string $style = 'solid',
        #[SkipDebug]
        public string $variation = 'background',
        #[SkipDebug]
        public string $rounded = 'lg',
        #[SkipDebug]
        public ComponentSlot|string|null $header = null,
        #[SkipDebug]
        public ComponentSlot|string|null $footer = null
    ) {
        $this->style = $this->light ? 'light' : 'solid';
        $this->variation = $this->bordered ? 'border' : 'background';
        $this->rounded = is_string($this->round) ? $this->round : 'lg';

        if ($this->minimize === 'mount') {
            $this->initializeMinimized = true;
        }
    }

    public function blade(): View
    {
        return view('ts-ui::components.card.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex justify-center gap-4 min-w-full',
                'second' => 'dark:bg-dark-700 flex w-full flex-col overflow-hidden bg-white shadow-md',
            ],
            'header' => [
                'wrapper' => [
                    'base' => 'flex items-center justify-between p-4',
                    'border' => 'dark:border-b-dark-600 border-b border-gray-100',
                    'minimize' => 'rounded-b-lg',
                ],
                'text' => [
                    'size' => 'text-md font-medium',
                    'color' => 'text-secondary-700 dark:text-dark-300',
                ],
            ],
            'body' => 'text-secondary-700 dark:text-dark-300 grow rounded-b-xl px-4 py-5',
            'footer' => [
                'wrapper' => 'text-secondary-700 dark:text-dark-300 dark:border-t-dark-600 rounded-lg rounded-t-none border-t border-t-secondary-200 p-4',
                'text' => 'flex items-center justify-end gap-2',
            ],
            'button' => [
                'minimize' => 'w-6 h-6',
                'maximize' => 'w-6 h-6',
                'close' => 'w-6 h-6',
            ],
            'image' => [
                'wrapper' => 'flex items-center gap-2',
                'rounded' => [
                    'top' => 'rounded-t-lg',
                    'bottom' => 'rounded-b-lg',
                ],
                'size' => 'w-full',
            ],
            'loading' => [
                'wrapper' => 'absolute top-0 left-0 right-0 h-0.5 overflow-hidden rounded-t-lg z-10',
                'bar' => 'h-full w-1/4 bg-primary-500 animate-indeterminate',
                'overlay' => 'absolute inset-0 z-10 cursor-not-allowed rounded-lg bg-white/50 dark:bg-dark-700/50',
            ],
            'border.radius' => [
                'xs' => 'rounded-xs',
                'sm' => 'rounded-sm',
                'md' => 'rounded-md',
                'lg' => 'rounded-lg',
                'xl' => 'rounded-xl',
                '2xl' => 'rounded-2xl',
            ],
        ]);
    }

    protected function validate(): void
    {
        if ($this->image !== null && $this->color !== null) {
            __ts_validation_exception($this, 'The [image] and [color] cannot be used together.');
        }

        if (! is_string($this->round)) {
            return;
        }

        $sizes = ['xs', 'sm', 'md', 'lg', 'xl', '2xl'];

        if (! in_array($this->round, $sizes, true)) {
            __ts_validation_exception($this, 'The [round] must be true or one of: ['.implode(', ', $sizes).'].');
        }
    }
}
