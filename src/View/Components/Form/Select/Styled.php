<?php

namespace TallStackUi\View\Components\Form\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\SelectStyledRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Floating;
use TallStackUi\View\Components\Form\Select\Traits\Setup;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use Throwable;

#[SoftPersonalization('select.styled')]
#[PassThroughRuntime(SelectStyledRuntime::class)]
class Styled extends TallStackUiComponent implements Personalization
{
    use DefaultInputClasses;
    use Setup;

    /** @throws Throwable */
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $placeholder = null,
        public string|array|null $request = null,
        public ?bool $multiple = false,
        public ?bool $searchable = false,
        public ?string $select = null,
        public ?array $selectable = [],
        public ?array $placeholders = null,
        public ?bool $invalidate = null,
        public ?bool $required = false,
        public ?int $limit = null,
        public ?int $lazy = null,
        public ?bool $grouped = null,
        public ?bool $unfiltered = null,
        #[SkipDebug]
        public Collection|array $options = [],
        #[SkipDebug]
        public ?string $after = null,
        #[SkipDebug]
        public ?bool $common = true,
    ) {
        $this->placeholders = array_merge(trans('tallstack-ui::messages.select'), $this->placeholders ?? []);
        $this->placeholder ??= data_get($this->placeholders, 'default');

        $this->common = ! filled($this->request);
        $this->searchable = $this->common ? $this->searchable : true;

        if (is_array($this->request)) {
            $this->request['method'] ??= 'get';
        }
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.select.styled');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [
                'wrapper' => [
                    'base' => 'dark:text-dark-300 dark:bg-dark-800 dark:focus:ring-primary-600 dark:disabled:bg-dark-600 dark:ring-dark-600 flex w-full cursor-pointer items-center gap-x-2 rounded-md border-0 bg-white py-1.5 text-sm ring-1 ring-gray-300 disabled:bg-gray-100 disabled:text-gray-500 disabled:ring-gray-300',
                    'color' => 'focus:ring-primary-600 text-gray-600 focus:outline-hidden focus:ring-2',
                    'error' => $this->error(),
                ],
                'content' => [
                    'wrapper' => [
                        'first' => 'relative inset-y-0 left-0 flex w-full items-center space-x-2 overflow-hidden rounded-lg pl-2',
                        'second' => 'flex items-center gap-2',
                    ],
                ],
            ],
            'buttons' => [
                'wrapper' => 'mr-2 flex items-center gap-1.5',
                'size' => 'h-5 w-5',
                'base' => 'dark:text-dark-400 text-gray-500 hover:text-red-500 dark:hover:text-red-500',
                'error' => 'text-red-500',
            ],
            'floating' => [
                'default' => collect(app(Floating::class)->personalization())->get('wrapper'),
                'class' => 'w-full overflow-auto',
            ],
            'box' => [
                'button' => [
                    'class' => 'absolute inset-y-0 right-2 flex cursor-pointer items-center px-2',
                    'icon' => 'dark:text-dark-400 h-5 w-5 text-gray-500 hover:text-red-500',
                ],
                'list' => [
                    'wrapper' => 'soft-scrollbar z-50 max-h-60 w-full overflow-auto text-base focus:outline-hidden sm:text-sm',
                    'loading' => [
                        'wrapper' => 'flex items-center justify-center space-x-4 p-4',
                        'class' => 'text-primary-600 dark:text-dark-400 h-12 w-12 animate-spin',
                    ],
                    'grouped' => [
                        'wrapper' => 'my-1 ml-2 text-gray-700 dark:text-dark-300 font-semibold',
                        'options' => 'flex items-center justify-between',
                        'base' => 'flex items-center truncate',
                        'image' => 'h-6 w-6 rounded-full',
                        'description' => [
                            'text' => 'text-xs font-normal opacity-70',
                            'wrapper' => 'flex flex-col ml-2',
                        ],
                    ],
                    'item' => [
                        'wrapper' => 'dark:text-dark-300 dark:hover:bg-dark-500 dark:focus:bg-dark-500 relative cursor-pointer select-none px-2 py-2 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden',
                        'options' => 'flex items-center justify-between',
                        'grouped' => 'flex items-center justify-between pl-3',
                        'base' => 'flex items-center truncate',
                        'selected' => 'font-semibold hover:bg-red-500 hover:text-white dark:hover:bg-red-500',
                        'disabled' => 'dark:bg-dark-500 !cursor-not-allowed bg-gray-100',
                        'image' => 'h-6 w-6 rounded-full',
                        'check' => 'h-5 w-5',
                        'description' => [
                            'text' => 'text-xs font-normal opacity-70',
                            'wrapper' => 'flex flex-col ml-2',
                        ],
                    ],
                    'empty' => 'dark:text-dark-300 block w-full pr-2 text-gray-600',
                ],
                'searchable' => [
                    'wrapper' => 'relative px-2 my-2',
                ],
            ],
            'items' => [
                'wrapper' => 'truncate',
                'placeholder' => [
                    'text' => 'dark:text-dark-400 truncate leading-6 text-gray-400',
                    'wrapper' => 'flex items-center',
                ],
                'single' => 'dark:text-dark-300 truncate leading-6 text-gray-600',
                'multiple' => [
                    'item' => 'dark:text-dark-100 dark:bg-dark-700 dark:ring-dark-600 inline-flex h-6 items-center space-x-1 rounded-lg bg-gray-100 px-2 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-200',
                    'label' => 'text-left',
                    'label.wrapper' => 'flex items-center',
                    'icon' => 'h-4 w-4 text-red-500',
                    'image' => 'w-3 h-3 mr-1 rounded-full',
                ],
                'image' => 'w-6 h-6 mr-1 rounded-full',
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (filled($this->options) && filled($this->request)) {
            __ts_validation_exception($this, 'The [options] and [request] cannot be defined at the same time.');
        }

        if ($this->common && ($this->lazy && $this->lazy < 10)) {
            __ts_validation_exception($this, 'The attribute [lazy] must be greater than or equal to 10.');
        }

        if ($this->common || ($this->request !== null && ! is_array($this->request))) {
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
