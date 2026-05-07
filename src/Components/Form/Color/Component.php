<?php

namespace TallStackUi\Components\Form\Color;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\ColorRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.color')]
#[PassThroughRuntime(ColorRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?bool $picker = false,
        public Collection|array|null $colors = null,
        public ?bool $invalidate = null,
        public ?bool $selectable = null,
        public ?bool $clearable = null,
        public string|array|null $excludedColor = null,
        public string|array|null $excludedStep = null,
        #[SkipDebug]
        public ?string $mode = null,
    ) {
        $this->mode = $this->picker ? 'picker' : 'range';

        $this->excludedColor = $this->excludedColor !== null
            ? (array) $this->excludedColor
            : [];

        $this->excludedStep = $this->excludedStep !== null
            ? array_map('strval', (array) $this->excludedStep)
            : [];
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.color');
    }

    public function customization(): array
    {
        return Arr::dot([
            'selected' => [
                'wrapper' => 'flex items-center',
                'base' => 'dark:border-dark-700 h-6 w-6 rounded-sm shadow',
            ],
            'icon' => [
                'class' => 'h-5 w-5',
            ],
            'floating' => [
                'default' => collect(app(Floating::class)->customization())->get('wrapper'),
                'class' => 'w-[18rem] overflow-auto',
            ],
            'box' => [
                'base' => 'shadow-sm dark:bg-dark-700 soft-scrollbar max-h-60 overflow-auto rounded-md bg-white py-4',
                'range' => [
                    'wrapper' => 'px-4',
                    'base' => 'mb-4 h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 dark:bg-gray-600',
                    'thumb' => '[&::-webkit-slider-thumb]:bg-primary-500 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                ],
                'button' => [
                    'wrapper' => 'mx-auto flex w-[17rem] flex-wrap items-center justify-center gap-1',
                    'color' => 'flex h-5 w-5 cursor-pointer items-center justify-center rounded-sm',
                    'icon' => 'h-3 w-3',
                ],
            ],
            'icon.wrapper' => 'flex items-center min-w-full gap-1.5',
            'icon.prefix-spacing' => 'ml-2 mr-1',
            'icon.suffix-spacing' => 'mr-2',
            'check' => [
                'light' => 'text-white',
                'dark' => 'text-dark-500',
            ],
            'clearable' => [
                'button' => 'cursor-pointer hover:text-red-500',
                'size' => 'h-5 w-5',
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (($colors = collect($this->colors))->isNotEmpty()) {
            $colors->each(function (string $color): void {
                if (! str($color)->startsWith('#')) {
                    __ts_validation_exception($this, 'All the [colors] must starts with #');
                }
            });
        }

        if (! $this->picker && $this->excludedStep) {
            __ts_validation_exception($this, 'The [excluded-step] attribute can only be used with [picker] attribute.');
        }

        $colors = [
            'slate', 'gray', 'zinc', 'neutral', 'stone',
            'red', 'orange', 'amber', 'yellow', 'lime',
            'green', 'emerald', 'teal', 'cyan', 'sky',
            'blue', 'indigo', 'violet', 'purple', 'fuchsia',
            'pink', 'rose', 'mauve', 'olive', 'mist', 'taupe',
        ];

        $steps = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900', '950'];

        foreach ($this->excludedColor as $color) {
            if (! in_array($color, $colors, true)) {
                __ts_validation_exception($this, "The [excluded-color] value [{$color}] is not a valid Tailwind CSS color.");
            }
        }

        foreach ($this->excludedStep as $step) {
            if (! in_array($step, $steps, true)) {
                __ts_validation_exception($this, "The [excluded-step] value [{$step}] is not a valid Tailwind CSS color step.");
            }
        }
    }
}
