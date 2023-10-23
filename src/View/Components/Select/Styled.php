<?php

namespace TallStackUi\View\Components\Select;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use InvalidArgumentException;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Components\Select\Traits\InteractsWithSelectOptions;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use Throwable;

class Styled extends Component implements Personalize
{
    use DefaultInputClasses;
    use InteractsWithSelectOptions;

    /** @throws Throwable */
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array $options = [],
        public string|array|null $request = null,
        public ?bool $multiple = false,
        public ?bool $searchable = false,
        public ?string $select = null,
        public ?array $selectable = [],
        public ?string $after = null,
        public ?bool $disabled = false,
        public array $placeholders = [],
        public ?bool $common = true,
        private readonly bool $ignoreValidations = false,
    ) {
        $this->placeholders = [...__('tallstack-ui::messages.select')];

        $this->common = ! filled($this->request);
        $this->searchable = ! $this->common ? true : $this->searchable;

        $this->options();
        $this->validate();

        if (filled($this->options) && filled($this->request)) {
            throw new InvalidArgumentException('You cannot define [options] and [request] at the same time.');
        }

        if (isset($this->options[0]) && (is_array($this->options[0]) && ! $this->select)) {
            throw new InvalidArgumentException('The [select] parameter must be defined.');
        }

        throw_if(blank($this->placeholders['default']), new Exception('The placeholder [default] cannot be empty.'));
        throw_if(blank($this->placeholders['search']), new Exception('The placeholder [search] cannot be empty.'));
        throw_if(blank($this->placeholders['empty']), new Exception('The placeholder [empty] cannot be empty.'));
    }

    public function personalization(): array
    {
        return Arr::dot([
            'button' => [
                'wrapper' => [
                    'base' => 'flex w-full cursor-pointer items-center gap-x-2 rounded-md border-0 bg-white py-1.5 shadow-sm ring-1 ring-inset text-sm leading-6 dark:text-dark-300 dark:bg-dark-800 disabled:bg-gray-50 dark:focus:ring-primary-600 disabled:text-gray-500 disabled:ring-gray-200 dark:disabled:bg-dark-600',
                    'color' => 'text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-600 ring-gray-300 dark:ring-dark-600',
                    'error' => $this->error(),
                ],
                'content' => 'relative inset-y-0 left-0 flex w-full items-center overflow-hidden rounded-lg pl-2 transition space-x-2',
            ],
            'itens' => [
                'multiple' => [
                    'item' => 'inline-flex items-center rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200 space-x-1 dark:text-dark-100 dark:bg-dark-700',
                    'icon' => 'h-4 w-4 text-red-500',
                ],
            ],
            'buttons' => [
                'wrapper' => 'mr-2 flex items-center',
                'size' => 'h-5 w-5',
                'base' => 'text-secondary-500 dark:text-dark-400 hover:text-red-500 dark:hover:text-red-500',
                'error' => 'text-red-500',
            ],
            'box' => [
                'wrapper' => 'absolute z-10 mt-1 w-full rounded-xl overflow-hidden bg-white shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-dark-700',
                'button' => [
                    'class' => 'absolute inset-y-0 right-2 flex cursor-pointer items-center px-2',
                    'icon' => 'h-5 w-5 transition text-secondary-500 hover:text-red-500',
                ],
                'list' => [
                    'wrapper' => 'z-50 max-h-60 w-full overflow-auto rounded-b-lg text-base soft-scrollbar focus:outline-none sm:text-sm',
                    'loading' => [
                        'wrapper' => 'flex items-center justify-center p-4 space-x-4',
                        'class' => 'h-12 w-12 animate-spin text-primary-600',
                    ],
                    'item' => [
                        'wrapper' => 'relative cursor-pointer select-none px-2 py-2 text-gray-700 transition hover:bg-gray-100 dark:text-dark-300 dark:hover:bg-dark-500 focus:outline-none focus:bg-gray-100 dark:focus:bg-dark-500',
                        'class' => 'flex items-center justify-between',
                    ],
                ],
            ],
            'message' => 'block w-full pr-2 text-gray-700 dark:text-dark-300',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.select.styled');
    }

    /** @throws Throwable */
    private function validate(): void
    {
        if ($this->common || $this->ignoreValidations) {
            return;
        }

        if (! $this->select) {
            throw new InvalidArgumentException('The [select] parameter must be defined');
        }

        if ($this->request && ! is_array($this->request)) {
            return;
        }

        if (! isset($this->request['url'])) {
            throw new InvalidArgumentException('The [url] is required in the request array');
        }

        $this->request['method'] ??= 'get';
        $this->request['method'] = strtolower($this->request['method']);

        // We remove search from the request because
        // the search will be attached on the javascript.
        unset($this->request['search']);

        if (! in_array($this->request['method'], ['get', 'post'])) {
            throw new InvalidArgumentException('The [method] must be get or post');
        }

        if (!isset($this->request['params'])) {
            return;
        }

        if (! is_array($this->request['params']) || blank($this->request['params'])) {
            throw new InvalidArgumentException('The [params] must be an array and cannot be empty');
        }
    }
}
