<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('form.color')]
class Color extends Component implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $id = null,
        public ?string $hint = null,
        public ?string $mode = null,
        public Collection|array $custom = [],
    ) {
        $this->id ??= uniqid();
        $this->mode ??= 'range';
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input()],
            'selected' => [
                'wrapper' => 'flex items-center pointer-events-none',
                'base' => 'w-6 h-6 border border-gray-300 dark:border-dark-700 rounded shadow',
            ],
            'icon' => [
                'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
                'class' => 'h-5 w-5 text-gray-400',
            ],
            'box' => [
                'wrapper' => 'absolute z-50 mt-2 border border-gray-300 dark:border-dark-900 rounded-md shadow-lg top-full overflow-hidden',
                'base' => 'p-2 bg-white rounded-md shadow-xs dark:bg-dark-800 overflow-auto soft-scrollbar max-h-60',
                'range' => [
                    'base' => 'w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 mb-4',
                    'thumb' => '[&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-primary-500',
                ],
                'button' => [
                    'wrapper' => 'flex flex-wrap items-center justify-center gap-1 w-[18rem] mx-auto',
                    'base' => 'rounded shadow-lg border dark:border-dark-900 ring-2 ring-transparent hover:ring-gray-500',
                    'color' => 'w-5 h-5 rounded cursor-pointer flex items-center justify-center',
                    'icon' => 'w-3 h-3',
                ],
            ],
            'animation' => [
                'enter' => 'transition duration-100 ease-out',
                'enter-start' => 'opacity-0 -translate-y-2',
                'enter-end' => 'opacity-100 translate-y-0',
                'leave' => 'transition ease-in duration-75',
                'leave-start' => 'opacity-100 scale-100',
                'leave-end' => 'opacity-0 scale-95',
            ],
            'error' => $this->error(),
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.color');
    }
}
