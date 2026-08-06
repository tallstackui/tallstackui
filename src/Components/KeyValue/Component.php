<?php

namespace TallStackUi\Components\KeyValue;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\RequireLivewireContext;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\KeyValueColors;
use TallStackUi\Support\Runtime\Components\KeyValueRuntime;
use TallStackUi\TallStackUiComponent;

#[RequireLivewireContext]
#[SoftCustomization('keyValue')]
#[ColorsThroughOf(KeyValueColors::class)]
#[PassThroughRuntime(KeyValueRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $label = null,
        public ?string $value = null,
        public ?string $color = null,
        public ?bool $colorless = null,
        public ?bool $compact = false,
        public ?int $limit = null,
        public ?bool $static = null,
        public ?bool $deletable = null,
        public ?string $deleteMethod = null,
        public ?bool $placeholders = true,
        public ComponentSlot|string|null $icon = null,
        #[SkipDebug]
        public ComponentSlot|string|null $header = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.key-value.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'dark:bg-dark-800 dark:border-dark-700 overflow-hidden rounded-lg border border-gray-200 bg-white text-sm',
            'header' => [
                'wrapper' => 'dark:border-dark-700 grid grid-cols-2 border-b border-gray-100 px-4 py-2',
                'wrapper-compact' => 'dark:border-dark-700 grid grid-cols-2 border-b border-gray-100 px-4 py-1',
                'neutral' => 'dark:text-dark-300 text-gray-600',
                'key' => 'font-semibold',
                'value' => 'font-semibold',
            ],
            'empty' => [
                'wrapper' => 'flex items-center justify-center py-5',
                'wrapper-compact' => 'flex items-center justify-center py-3',
                'text' => 'dark:text-dark-400 text-gray-500',
            ],
            'list' => [
                'wrapper' => 'grid grid-cols-2 px-4 items-center relative dark:text-dark-200 text-gray-600',
                'wrapper-default-padding' => 'py-4',
                'wrapper-default-padding-compact' => 'py-2.5',
                'divider' => 'divide-y divide-gray-100 dark:divide-dark-700',
                'value-wrapper' => 'pr-8 mr-2',
                'value-wrapper-deletable' => 'top-2',
                'input' => [
                    'key' => 'dark:bg-dark-800 dark:placeholder:text-dark-400 w-full border-0 bg-transparent focus:ring-0 focus:outline-none dark:text-white',
                    'value' => 'dark:bg-dark-800 dark:placeholder:text-dark-400 w-full border-0 bg-transparent focus:ring-0 focus:outline-none dark:text-white',
                ],
            ],
            'button' => [
                'add' => 'dark:border-dark-700 w-full cursor-pointer border-t border-gray-100 px-4 py-2 text-center',
                'add-compact' => 'dark:border-dark-600 w-full cursor-pointer border-t border-gray-100 px-4 py-1 text-center',
                'neutral' => 'dark:text-dark-300 dark:hover:bg-dark-600 text-gray-600 hover:bg-gray-50',
                'delete' => 'absolute top-2 right-0 h-5 w-5 text-red-500',
            ],
        ]);
    }

    protected function validate(): void
    {
        if ($this->static && $this->limit) {
            __ts_validation_exception($this, 'The [static] and [limit] attributes cannot be used at the same time.');
        }
    }
}
