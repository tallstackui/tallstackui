<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
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
        public ?string $hint = null,
        public ?string $text = null,
        public ?bool $icon = null,
        public ?bool $left = false,
        public ?bool $secret = false,
        public ?array $icons = ['copy' => null, 'copied' => null],
        #[SkipDebug]
        public ?array $placeholders = [],
        #[SkipDebug]
        public ?string $type = null,
    ) {
        $this->placeholders = __('tallstack-ui::messages.clipboard');

        $this->type = $this->icon ? 'icon' : 'input';

        $this->icons['copy'] ??= '';
        $this->icons['copied'] ??= '';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.clipboard');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'wrapper' => 'relative flex flex-grow items-stretch ring-inset focus-within:z-10',
                'buttons' => [
                    'base' => 'dark:ring-dark-600 dark:text-dark-300 dark:bg-dark-700 relative inline-flex items-center gap-x-1.5 bg-white px-2 py-2 text-xs font-semibold uppercase text-gray-700 ring-1 ring-gray-300 transition',
                    'left' => '-mr-px rounded-l-md',
                    'right' => '-ml-px rounded-r-md',
                    'icon.class' => 'text-primary-500 dark:text-dark-300 h-4 w-4 cursor-pointer',
                ],
                'base' => 'focus:ring-primary-600 dark:focus:ring-primary-600 block w-full rounded-none border-0 py-1.5 text-gray-900 ring-1 ring-gray-300 transition placeholder:text-gray-400 focus:ring-2 sm:text-sm sm:leading-6',
                'color' => [...$this->input()['color']],
                'sides' => [
                    'left' => 'rounded-r-md',
                    'right' => 'rounded-l-md',
                ],
            ],
            'icon' => [
                'wrapper' => 'inline-flex',
                'icons' => [
                    'copy' => [
                        'name' => 'clipboard',
                        'class' => 'text-primary-500 dark:text-dark-300 h-5 w-5 cursor-pointer',
                    ],
                    'copied' => [
                        'name' => 'document-check',
                        'class' => 'h-5 w-5 cursor-none text-green-500',
                    ],
                ],
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    public function validating(?string $text = null): void
    {
        if (! $text) {
            throw new InvalidArgumentException('The clipboard [text] cannot be empty. You should specify the text using property or slot.');
        }
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        $messages = __('tallstack-ui::messages.clipboard');

        if (blank(data_get($messages, 'button.copy'))) {
            throw new InvalidArgumentException('The clipboard [button.copy] message cannot be empty.');
        }

        if (blank(data_get($messages, 'button.copied'))) {
            throw new InvalidArgumentException('The clipboard [button.copied] message cannot be empty.');
        }
    }
}
