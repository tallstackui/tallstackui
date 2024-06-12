<?php

namespace TallStackUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Traits\SanitizePropertyValue;
use TallStackUi\Foundation\Traits\WireChangeEvent;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Components\Select\Traits\InteractsWithSelectOptions;
use Throwable;

#[SoftPersonalization('select.styled')]
class Styled extends BaseComponent implements Personalization
{
    use DefaultInputClasses;
    use InteractsWithSelectOptions;
    use SanitizePropertyValue;
    use WireChangeEvent;

    /** @throws Throwable */
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $placeholder = null,
        #[SkipDebug]
        public Collection|array $options = [],
        public string|array|null $request = null,
        public ?bool $multiple = false,
        public ?bool $searchable = false,
        public ?string $select = null,
        public ?array $selectable = [],
        #[SkipDebug]
        public ?string $after = null,
        public ?bool $disabled = false,
        #[SkipDebug]
        public ?bool $common = true,
        public ?array $placeholders = null,
        public ?bool $invalidate = null,
        public ?bool $required = false,
        public ?int $limit = null,
    ) {
        $this->placeholders ??= [...__('tallstack-ui::messages.select')];
        $this->placeholder ??= data_get($this->placeholders, 'default');

        $this->common = ! filled($this->request);
        $this->searchable = $this->common ? $this->searchable : true;

        $this->options();

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
                    'base' => 'dark:text-dark-300 dark:bg-dark-800 dark:focus:ring-primary-600 dark:disabled:bg-dark-600 dark:ring-dark-600 mt-1 flex w-full cursor-pointer items-center gap-x-2 rounded-md border-0 bg-white py-1.5 text-sm ring-1 ring-gray-300 transition disabled:bg-gray-100 disabled:text-gray-500 disabled:ring-gray-300',
                    'color' => 'focus:ring-primary-600 text-gray-600 focus:outline-none focus:ring-2',
                    'error' => $this->error(),
                ],
                'content' => 'relative inset-y-0 left-0 flex w-full items-center space-x-2 overflow-hidden rounded-lg pl-2 transition',
            ],
            'buttons' => [
                'wrapper' => 'mr-2 flex items-center',
                'size' => 'h-5 w-5',
                'base' => 'dark:text-dark-400 text-gray-500 hover:text-red-500 dark:hover:text-red-500',
                'error' => 'text-red-500',
            ],
            'box' => [
                'button' => [
                    'class' => 'absolute inset-y-0 right-2 flex cursor-pointer items-center px-2',
                    'icon' => 'dark:text-dark-400 h-5 w-5 text-gray-500 transition hover:text-red-500',
                ],
                'list' => [
                    'wrapper' => 'soft-scrollbar z-50 max-h-60 w-full overflow-auto text-base focus:outline-none sm:text-sm',
                    'loading' => [
                        'wrapper' => 'flex items-center justify-center space-x-4 p-4',
                        'class' => 'text-primary-600 dark:text-dark-400 h-12 w-12 animate-spin',
                    ],
                    'item' => [
                        'wrapper' => 'dark:text-dark-300 dark:hover:bg-dark-500 dark:focus:bg-dark-500 relative cursor-pointer select-none px-2 py-2 text-gray-700 transition hover:bg-gray-100 focus:bg-gray-100 focus:outline-none',
                        'options' => 'flex items-center justify-between',
                        'base' => 'flex items-center truncate',
                        'selected' => 'font-semibold hover:bg-red-500 hover:text-white dark:hover:bg-red-500',
                        'disabled' => 'dark:bg-dark-500 !cursor-not-allowed bg-gray-100',
                        'image' => 'h-6 w-6 rounded-full',
                        'check' => 'h-5 w-5',
                        'description' => 'text-xs font-normal opacity-70',
                    ],
                    'empty' => 'dark:text-dark-300 block w-full pr-2 text-gray-600',
                ],
            ],
            'itens' => [
                'wrapper' => 'truncate',
                'placeholder' => 'dark:text-dark-400 truncate leading-6 text-gray-400',
                'single' => 'dark:text-dark-300 truncate leading-6 text-gray-600',
                'multiple' => [
                    'item' => 'dark:text-dark-100 dark:bg-dark-700 dark:ring-dark-600 inline-flex h-6 items-center space-x-1 rounded-lg bg-gray-100 px-2 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-200',
                    'label' => 'text-left',
                    'icon' => 'h-4 w-4 text-red-500',
                ],
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (filled($this->options) && filled($this->request)) {
            throw new InvalidArgumentException('The [select.styled] [options] and [request] cannot be defined at the same time.');
        }

        if (($this->common && isset($this->options[0]) && (is_array($this->options[0]) && ! $this->select)) || ! $this->common && ! $this->select) {
            throw new InvalidArgumentException('The [select.styled] parameter [select] must be defined.');
        }

        if ($this->common || $this->request && ! is_array($this->request)) {
            return;
        }

        if (! isset($this->request['url'])) {
            throw new InvalidArgumentException('The [select.styled] parameter [url] is required in the request array.');
        }

        $this->request['method'] = strtolower((string) $this->request['method']);

        if (! in_array($this->request['method'], ['get', 'post'])) {
            throw new InvalidArgumentException('The [select.styled] parameter [method] must be get or post.');
        }

        if (! isset($this->request['params'])) {
            return;
        }

        if (! is_array($this->request['params']) || blank($this->request['params'])) {
            throw new InvalidArgumentException('The [select.styled] parameter [params] must be an array and cannot be empty.');
        }
    }
}
