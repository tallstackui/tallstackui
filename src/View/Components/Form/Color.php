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
        public ?bool $all = false,
        public Collection|array $colors = [],
        public ?string $mode = null,
    ) {
        $this->id ??= uniqid();
        $this->mode = $this->all ? 'all' : 'range';
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input()],
            'selected' => [
                'wrapper' => 'flex items-center',
                'base' => 'dark:border-dark-700 h-6 w-6 rounded border border-gray-300 shadow',
            ],
            'icon' => [
                'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
                'class' => 'h-5 w-5 text-gray-400',
            ],
            'box' => [
                'wrapper' => 'dark:border-dark-900 absolute top-full z-50 mt-2 overflow-hidden rounded-md border border-gray-300 shadow-lg',
                'base' => 'shadow-xs dark:bg-dark-800 soft-scrollbar max-h-60 overflow-auto rounded-md bg-white p-2',
                'range' => [
                    'base' => 'mb-4 h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 dark:bg-gray-700',
                    'thumb' => '[&::-webkit-slider-thumb]:bg-primary-500 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                ],
                'button' => [
                    'wrapper' => 'mx-auto flex w-[18rem] flex-wrap items-center justify-center gap-1',
                    'base' => 'dark:border-dark-900 rounded border shadow-lg ring-2 ring-transparent hover:ring-gray-500',
                    'color' => 'flex h-5 w-5 cursor-pointer items-center justify-center rounded',
                    'icon' => 'h-3 w-3',
                ],
            ],
            'error' => $this->error(),
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.color');
    }
}
