<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\RequireLivewireContext;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\KeyValueRuntime;
use TallStackUi\TallStackUiComponent;

#[RequireLivewireContext]
#[SoftPersonalization('keyValue')]
#[PassThroughRuntime(KeyValueRuntime::class)]
class KeyValue extends TallStackUiComponent implements Personalization
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
        return view('tallstack-ui::components.key-value');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'dark:bg-dark-600 dark:border-dark-600 overflow-hidden rounded-lg border border-gray-200 bg-gray-100 text-sm',
                'second' => 'dark:divide-dark-500 divide-y divide-gray-300',
            ],
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
                'input' => [
                    'key' => 'background-transparent dark:bg-dark-600 dark:placeholder:text-dark-400 w-full border-0 bg-gray-100 focus:ring-0 focus:outline-none dark:text-white',
                    'value' => 'background-transparent dark:bg-dark-600 dark:placeholder:text-dark-400 w-full border-0 bg-gray-100 focus:ring-0 focus:outline-none dark:text-white',
                ],
            ],
            'button' => [
                'add' => 'dark:bg-dark-700 dark:text-dark-300 w-full cursor-pointer bg-gray-200 px-4 py-2 text-center text-gray-600 hover:underline',
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
