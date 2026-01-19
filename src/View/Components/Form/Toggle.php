<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\ColorsThroughOf;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Colors\Components\ToggleColors;
use TallStackUi\Foundation\Support\Runtime\Components\CheckboxRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Form\Traits\Setup;

#[SoftPersonalization('form.toggle')]
#[ColorsThroughOf(ToggleColors::class)]
#[PassThroughRuntime(CheckboxRuntime::class)]
class Toggle extends TallStackUiComponent implements Personalization
{
    use Setup;

    public function __construct(
        public ComponentSlot|string|null $label = null,
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
        return view('tallstack-ui::components.form.toggle');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'relative flex items-center justify-end',
            'input' => [
                'class' => 'peer absolute inset-y-0 left-0.5 translate-x-0 my-0.5 transform cursor-pointer appearance-none rounded-full border-0 bg-white shadow checked:bg-none checked:text-white focus:outline-hidden ring-0 ring-offset-0 focus:ring-0 focus:ring-offset-0',
                'sizes' => [
                    'xs' => 'h-2 w-2 checked:translate-x-2',
                    'sm' => 'h-3 w-3 checked:translate-x-3',
                    'md' => 'h-4 w-4 checked:translate-x-4',
                    'lg' => 'h-5 w-5 checked:translate-x-4',
                ],
            ],
            'background' => [
                'class' => 'bg-secondary-200 dark:bg-dark-800 block cursor-pointer rounded-full group-focus:ring-0 group-focus:ring-offset-0 peer-focus:ring-0 peer-focus:ring-offset-0',
                'sizes' => [
                    'xs' => 'h-3 w-5',
                    'sm' => 'h-4 w-7',
                    'md' => 'h-5 w-9',
                    'lg' => 'h-6 w-10',
                ],
            ],
            'error' => 'bg-red-600 group-focus:ring-red-600 peer-checked:bg-red-600 peer-focus:ring-red-600',
        ]);
    }
}
