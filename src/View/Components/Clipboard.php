<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('clipboard')]
class Clipboard extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $text = null,
        public ?bool $icon = null,
        public ?bool $left = false,
        #[SkipDebug]
        public ?array $placeholders = [],
        #[SkipDebug]
        public ?string $type = null,
    ) {
        // TODO: validate?
        $this->placeholders = __('tallstack-ui::messages.clipboard');

        $this->type = ! $this->icon ? 'input' : 'icon';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.clipboard');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'wrapper' => 'relative flex flex-grow items-stretch focus-within:z-10',
                'buttons' => [
                    'base' => 'relative inline-flex items-center gap-x-1.5 px-3 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-dark-600 dark:text-dark-300 transition',
                    'left' => 'rounded-l-md -mr-px',
                    'right' => 'rounded-r-md -ml-px',
                    'icon' => [
                        'name' => 'clipboard-document',
                        'class' => 'h-5 w-5 text-primary-500 cursor-pointer',
                    ],
                ],
                'class' => [
                    'base' => 'focus:ring-primary-600 dark:focus:ring-primary-600 block w-full rounded-none border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 transition placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6',
                    'color' => [...$this->input()['color']],
                ],
            ],
            'icon' => [
                'wrapper' => 'inline-flex',
                'icons' => [
                    'copy' => [
                        'name' => 'clipboard',
                        'class' => 'h-5 w-5 text-primary-500 cursor-pointer',
                    ],
                    'copied' => [
                        'name' => 'document-check',
                        'class' => 'h-5 w-5 text-green-500 cursor-none',
                    ],
                ],
            ],
        ]);
    }
}
