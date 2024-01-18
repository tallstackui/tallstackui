<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.fileupload')]
class FileUpload extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $invalidate = null,
        public ?bool $multiple = false,
    ) {
        //$this->mode = $this->picker ? 'picker' : 'range';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.fileupload');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input()],
            'selected' => [
                'wrapper' => 'flex items-center',
                'base' => 'dark:border-dark-700 h-6 w-6 rounded shadow',
            ],
            'icon' => [
                'wrapper' => 'absolute inset-y-0 right-0 flex items-center pr-2.5',
                'class' => 'h-5 w-5 text-gray-400',
            ],
            'box' => [
                'wrapper' => 'dark:border-dark-600 absolute top-full z-50 mt-2 overflow-hidden rounded-md border border-gray-200 shadow-lg w-full',
                'base' => 'shadow-xs dark:bg-dark-800 rounded-md bg-white p-2',
                'range' => [
                    'base' => 'mb-4 h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 dark:bg-gray-700',
                    'thumb' => '[&::-webkit-slider-thumb]:bg-primary-500 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                ],
                'button' => [
                    'wrapper' => 'mx-auto flex flex-wrap items-center justify-center gap-1',
                    'base' => 'rounded shadow-lg',
                    'color' => 'flex h-5 w-5 cursor-pointer items-center justify-center rounded',
                    'icon' => 'h-3 w-3',
                ],
            ],
            'error' => $this->error(),
        ]);
    }
}
