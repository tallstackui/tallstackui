<?php

namespace TallStackUi\View\Components\Form;

use Exception;
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
        public ?string $prefix = null,
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
            'wrapper' => 'flex flex-wrap items-center gap-1 border-0 px-2 py-1.5 pr-4',
            'label' => [
                'base' => 'inline-flex h-6 items-center rounded-lg bg-gray-100 px-1 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-200 space-x-1 dark:text-dark-100 dark:bg-dark-700 dark:ring-dark-600',
                'icon' => 'h-4 w-4 cursor-pointer text-red-500',
            ],
            'input' => [
                'base' => 'flex flex-grow items-center border-0 border-transparent py-0 px-1 text-gray-600 outline-none focus:outline-none focus:ring-0 !bg-transparent',
                ...collect($this->input())->except('base')->toArray(),
            ],
            'button' => [
                'wrapper' => 'text-secondary-500 dark:text-dark-400 absolute inset-y-0 right-2 flex cursor-pointer items-center',
                'icon' => 'h-5 w-5 text-red-500',
            ],
            'error' => $this->error(),
        ]);
    }

    /** @throws Exception */
    protected function validate(): void
    {
        if (! $this->prefix) {
            return;
        }

        if (strlen($this->prefix) > 1) {
            throw new Exception('The tag [prefix] must be a single character.');
        }
    }
}
