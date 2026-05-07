<?php

namespace TallStackUi\Components\Form\InputSelect;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\FormDefaultInputClasses;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\InputSelectRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.input.select')]
#[PassThroughRuntime(InputSelectRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use FormDefaultInputClasses;

    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?string $icon = null,
        public ?bool $clearable = null,
        public ?bool $invalidate = null,
        #[SkipDebug]
        public ?string $position = 'left',
        #[SkipDebug]
        public ComponentSlot|string|null $prefix = null,
        #[SkipDebug]
        public ComponentSlot|string|null $suffix = null,
    ) {
        $this->position = $this->position === 'left' ? 'left' : 'right';
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.input-select');
    }

    public function customization(): array
    {
        $input = $this->input();

        return Arr::dot([
            'input' => [
                ...$input,
                'wrapper' => [
                    'first' => 'flex w-full rounded-md ring-1 focus-within:ring-2 focus-within:ring-primary-600 dark:focus-within:ring-primary-600',
                    'second' => $input['wrapper'],
                    'round' => [
                        'left' => 'rounded-l-none!',
                        'right' => 'rounded-r-none!',
                    ],
                    'error' => 'ring-red-300 focus-within:ring-red-500 dark:ring-red-500 dark:focus-within:ring-red-500',
                ],
                'paddings' => [
                    'prefix' => 'pr-2 pl-0',
                    'suffix' => 'pl-2 pr-0',
                    'left' => 'pl-8',
                    'right' => 'pr-8',
                    'clearable' => 'pr-14!',
                    'icon-clearable-extra' => 'pr-8!',
                ],
                'wrapper-fix' => 'ring-0! focus-within:ring-0!',
                'slot-prefix-spacing' => 'ml-2 mr-1',
                'slot-suffix-spacing' => 'ml-1 mr-2',
            ],
            'icon' => [
                'wrapper' => 'pointer-events-none absolute inset-y-0 flex items-center text-gray-500 dark:text-dark-400',
                'paddings' => [
                    'left' => 'left-0 pl-2',
                    'right' => 'right-0 pr-2',
                ],
                'size' => 'h-5 w-5',
                'color' => 'text-gray-500 dark:text-dark-400',
            ],
            'clearable' => [
                'wrapper' => 'cursor-pointer absolute inset-y-0 flex items-center text-gray-500 dark:text-dark-400',
                'padding' => 'right-0 pr-2',
                'size' => 'h-5 w-5',
                'color' => 'hover:text-red-500',
            ],
            'error' => $this->error(),
        ]);
    }

    protected function validate(): void
    {
        if ($this->icon && (($this->position === 'left' && $this->prefix !== null) || ($this->position === 'right' && $this->suffix !== null))) {
            __ts_validation_exception($this, 'The [icon] cannot be used with [prefix] or [suffix] at the same side');
        }

        if ($this->clearable && $this->suffix !== null) {
            __ts_validation_exception($this, 'The [clearable] cannot be used with [suffix]');
        }
    }
}
