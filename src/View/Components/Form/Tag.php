<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Traits\SanitizePropertyValue;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.tag')]
class Tag extends BaseComponent implements Personalization
{
    use DefaultInputClasses;
    use SanitizePropertyValue;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?int $limit = null,
        public ?bool $invalidate = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.tag');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex flex-wrap gap-x-2 border-0 bg-white pr-4 pb-1.5 dark:bg-dark-800',
            'label' => [
                'base' => 'inline-flex h-8 items-center rounded-lg bg-gray-100 px-2 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-200 mt-1.5 py space-x-1 dark:text-dark-100 dark:bg-dark-700 dark:ring-dark-600',
                'icon' => 'h-4 w-4 cursor-pointer text-red-500',
            ],
            'input' => [
                'class' => [
                    'base' => 'flex flex-grow items-center border-0 border-transparent pb-1 text-gray-600 outline-none pt focus:outline-none focus:ring-0',
                    ...collect($this->input())->except('base')->toArray(),
                ],
            ],
            'button' => [
                'wrapper' => 'absolute inset-y-0 right-2 flex items-center text-secondary-500 dark:text-dark-400',
                'base' => 'h-5 w-5 cursor-pointer text-red-500',
            ],
            'error' => $this->error(),
        ]);
    }
}
