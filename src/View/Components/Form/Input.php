<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\InputRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.input')]
#[PassThroughRuntime(InputRuntime::class)]
class Input extends TallStackUiComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?string $icon = null,
        public ?bool $clearable = null,
        public ?bool $invalidate = null,
        public ?bool $stripZeros = null,
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
        return view('tallstack-ui::components.form.input');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                ...$this->input(),
                'paddings' => [
                    'prefix' => 'pr-3 pl-0',
                    'suffix' => 'pl-3 pr-0',
                    'left' => 'pl-8',
                    'right' => 'pr-8',
                    'clearable' => '!pr-14',
                ],
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
