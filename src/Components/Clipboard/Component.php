<?php

namespace TallStackUi\Components\Clipboard;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\FormDefaultInputClasses;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\ClipboardRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('clipboard')]
#[PassThroughRuntime(ClipboardRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use FormDefaultInputClasses;

    public const STATES = ['copy', 'copied'];

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $text = null,
        public bool|array|null $icon = null,
        public ?bool $left = false,
        public ?bool $secret = false,
        #[SkipDebug]
        public ?array $placeholders = [],
        #[SkipDebug]
        public ?array $icons = null,
        #[SkipDebug]
        public ?string $type = null,
    ) {
        $this->placeholders = trans('ts-ui::messages.clipboard');

        $this->type = match ($this->icon) {
            null, false => 'input',
            default => 'icon',
        };

        $this->icons = [
            'copy' => is_array($this->icon) ? ($this->icon['copy'] ?? '') : '',
            'copied' => is_array($this->icon) ? ($this->icon['copied'] ?? '') : '',
        ];
    }

    public function blade(): View
    {
        return view('ts-ui::components.clipboard.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'spacing-top' => 'mt-1',
                'base' => 'dark:ring-dark-600/50 dark:focus-within:ring-primary-600 flex rounded-md ring-1 ring-gray-200 focus-within:ring-2 focus-within:ring-primary-600',
            ],
            'input' => [
                'wrapper' => 'relative flex grow items-stretch',
                'buttons' => [
                    'base' => 'dark:text-dark-300 dark:bg-dark-800 relative inline-flex items-center gap-x-1.5 bg-white px-2 py-2 text-xs font-semibold uppercase text-gray-700 cursor-pointer',
                    'left' => 'dark:border-dark-600/50 rounded-l-md border-r border-gray-200',
                    'right' => 'dark:border-dark-600/50 rounded-r-md border-l border-gray-200',
                    'icon.class' => 'text-primary-500 dark:text-dark-300 h-4 w-4 cursor-pointer',
                    'text' => [
                        'left' => '',
                        'right' => '',
                    ],
                ],
                'base' => 'block w-full rounded-none border-0 py-1.5 text-gray-900 ring-0 placeholder:text-gray-400 focus:outline-hidden focus:ring-transparent sm:text-sm sm:leading-6',
                'color' => [
                    ...$this->input()['color'],
                    'base' => 'dark:text-dark-300 text-gray-600',
                ],
                'sides' => [
                    'left' => 'rounded-r-md',
                    'right' => 'rounded-l-md',
                ],
            ],
            'icon' => [
                'wrapper' => 'inline-flex cursor-pointer',
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
            __ts_validation_exception($this, 'The [text] cannot be empty. You should specify the text using property or slot.');
        }
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (is_array($this->icon) && ($invalid = array_diff(array_keys($this->icon), self::STATES)) !== []) {
            __ts_validation_exception($this, 'The [icon] array only accepts the keys ['.implode(', ', self::STATES).']. Received: ['.implode(', ', $invalid).'].');
        }

        $messages = trans('ts-ui::messages.clipboard');

        if (blank(data_get($messages, 'button.copy'))) {
            __ts_validation_exception($this, 'The [button.copy] message cannot be empty.');
        }

        if (blank(data_get($messages, 'button.copied'))) {
            __ts_validation_exception($this, 'The [button.copied] message cannot be empty.');
        }
    }
}
