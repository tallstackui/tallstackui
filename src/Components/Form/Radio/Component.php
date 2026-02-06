<?php

namespace TallStackUi\Components\Form\Radio;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\FormSetup;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\RadioColors;
use TallStackUi\Support\Runtime\Components\CheckboxRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.radio')]
#[ColorsThroughOf(RadioColors::class)]
#[PassThroughRuntime(CheckboxRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use FormSetup;

    public function __construct(
        public string|null|ComponentSlot $label = null,
        public ?string $xs = null,
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $position = 'right',
        public ?string $color = 'primary',
        public ?bool $invalidate = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.radio');
    }

    public function customization(): array
    {
        return Arr::dot([
            'input' => [
                'class' => 'form-radio dark:border-dark-600 border-1 dark:bg-dark-800 rounded-full border-gray-300 bg-white ring-0 ring-offset-0 focus:ring-0 focus:ring-offset-0',
                'sizes' => [
                    'xs' => 'h-3 w-3',
                    'sm' => 'h-4 w-4',
                    'md' => 'h-5 w-5',
                    'lg' => 'h-6 w-6',
                ],
            ],
            'error' => 'border-red-300 text-red-600 focus:border-red-400 focus:ring-red-600',
        ]);
    }
}
