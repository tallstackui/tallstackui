<?php

namespace TallStackUi\Components\KeyValue;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\RequireLivewireContext;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\KeyValueRuntime;
use TallStackUi\TallStackUiComponent;

#[RequireLivewireContext]
#[SoftCustomization('keyValue')]
#[PassThroughRuntime(KeyValueRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $label = null,
        public ?string $value = null,
        public ?int $limit = null,
        public ?bool $static = null,
        public ?bool $deletable = null,
        public ?string $deleteMethod = null,
        public ?bool $placeholders = true,
        public ComponentSlot|string|null $icon = null,
        #[SkipDebug]
        public ?ComponentSlot $header = null,
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
            'wrapper' => 'dark:bg-dark-600 dark:border-dark-600 overflow-hidden rounded-lg border border-gray-200 bg-gray-100 text-sm',
            'header' => [
                'wrapper' => 'dark:text-dark-300 dark:bg-dark-700 grid grid-cols-2 bg-gray-200 px-4 py-2 text-gray-600',
                'key' => 'font-semibold',
                'value' => 'font-semibold',
            ],
            'empty' => [
                'wrapper' => 'flex items-center justify-center py-5',
                'text' => 'dark:text-dark-300 text-gray-500',
            ],
            'list' => [
                'wrapper' => 'grid grid-cols-2 px-4 items-center relative text-gray-600',
                'wrapper-default-padding' => 'py-4',
                'divider' => 'divide-y divide-gray-300 dark:divide-dark-500',
                'value-wrapper' => 'pr-8 mr-2',
                'value-wrapper-deletable' => 'top-2',
                'input' => [
                    'key' => 'background-transparent dark:bg-dark-600 dark:placeholder:text-dark-400 w-full border-0 bg-gray-100 focus:ring-0 focus:outline-none dark:text-white',
                    'value' => 'background-transparent dark:bg-dark-600 dark:placeholder:text-dark-400 w-full border-0 bg-gray-100 focus:ring-0 focus:outline-none dark:text-white',
                ],
            ],
            'button' => [
                'add' => 'dark:bg-dark-700 dark:text-dark-300 w-full cursor-pointer bg-gray-200 px-4 py-2 text-center text-gray-600',
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
