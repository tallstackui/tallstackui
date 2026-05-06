<?php

namespace TallStackUi\Components\Form\Autocomplete;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\AutocompleteRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.autocomplete')]
#[PassThroughRuntime(AutocompleteRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $placeholder = null,
        public Collection|array|null $items = null,
        public string|array|null $request = null,
        public ?string $prefix = null,
        public ?string $suffix = null,
        public ?bool $clearable = null,
        public ?bool $invalidate = null,
        public ?bool $strict = null,
        public ?int $lazy = null,
        public ?bool $disabled = null,
        #[SkipDebug]
        public ?array $placeholders = null,
        #[SkipDebug]
        public ComponentSlot|string|null $after = null,
    ) {
        $this->placeholders = array_merge(trans('ts-ui::messages.autocomplete'), $this->placeholders ?? []);
        $this->placeholder ??= data_get($this->placeholders, 'default');

        $this->items = collect($this->items)->values()->all();

        if (is_string($this->request)) {
            $this->request = ['url' => $this->request, 'method' => 'get'];
        } elseif (is_array($this->request)) {
            $this->request['method'] ??= 'get';
        }
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.autocomplete');
    }

    public function customization(): array
    {
        return Arr::dot([
            'adornment' => [
                'suffix' => 'dark:text-dark-400 flex select-none items-center whitespace-nowrap text-gray-500 text-sm',
            ],
            'icon' => [
                'wrapper' => 'mr-2 flex items-center gap-1.5',
                'clear' => 'h-5 w-5 cursor-pointer text-gray-500 hover:text-red-500 dark:text-dark-400 dark:hover:text-red-500',
                'loading' => 'h-5 w-5 animate-spin text-primary-500 dark:text-dark-400',
            ],
            'floating' => [
                'default' => collect(app(Floating::class)->customization())->get('wrapper'),
                // z-40! overrides the Floating wrapper's default z-50 so the dropdown stays under Dialog/Modal overlays (also z-50).
                'class' => 'overflow-auto z-40!',
            ],
            'box' => [
                'list' => [
                    'wrapper' => 'custom-scrollbar z-50 max-h-60 w-full overflow-auto text-base focus:outline-hidden sm:text-sm',
                    'item' => [
                        'wrapper' => 'dark:text-dark-300 dark:hover:bg-dark-500 relative cursor-pointer select-none px-2 py-2 text-gray-700 hover:bg-gray-100',
                        'base' => 'flex items-center gap-2',
                        'value' => 'truncate font-normal',
                        'description' => 'mt-0.5 truncate text-xs font-normal opacity-70',
                        'image' => 'h-8 w-8 shrink-0 rounded-full object-cover',
                        'content' => 'flex min-w-0 flex-col',
                        'highlighted' => 'bg-gray-100 dark:bg-dark-500',
                        'selected' => 'font-semibold',
                        'disabled' => 'cursor-not-allowed opacity-50 hover:bg-transparent dark:hover:bg-transparent',
                    ],
                    'empty' => 'block w-full px-3 py-2 text-sm text-gray-600 dark:text-dark-300',
                    'loading' => [
                        'wrapper' => 'flex items-center justify-center space-x-4 p-4',
                        'class' => 'text-primary-600 dark:text-dark-400 h-12 w-12 animate-spin',
                    ],
                ],
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (filled($this->items) && filled($this->request)) {
            __ts_validation_exception($this, 'The [items] and [request] cannot be defined at the same time.');
        }

        if (! is_array($this->request)) {
            return;
        }

        if (! isset($this->request['url'])) {
            __ts_validation_exception($this, 'The attribute [url] is required in the request array.');
        }

        $this->request['method'] = strtolower((string) $this->request['method']);

        if (! in_array($this->request['method'], ['get', 'post'])) {
            __ts_validation_exception($this, 'The attribute [method] must be "get" or "post".');
        }

        if (! isset($this->request['params'])) {
            return;
        }

        if (! is_array($this->request['params']) || blank($this->request['params'])) {
            __ts_validation_exception($this, 'The attribute [params] must be an array and cannot be empty.');
        }
    }
}
