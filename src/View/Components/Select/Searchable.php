<?php

namespace TallStackUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use InvalidArgumentException;
use Throwable;

class Searchable extends Styled
{
    public function __construct(
        public string|array|null $request = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $multiple = false,
        public ?string $select = null,
        public ?array $selectable = [],
        public ?string $after = null,
        public ?string $before = null,
        private readonly bool $ignoreValidations = false,
    ) {
        parent::__construct(
            label: $label,
            hint: $hint,
            multiple: $multiple,
            select: $select,
            selectable: $selectable,
        );

        $this->request();
    }

    public function render(): View
    {
        return view('tallstack-ui::components.select.searchable', [
            'placeholder' => __('tallstack-ui::messages.select.placeholder'),
        ]);
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function tallStackUiClasses(): array
    {
        return [
            'multiple' => 'inline-flex items-center rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 space-x-1',
            'icon' => 'h-4 w-4 text-red-500 transition hover:text-red-500',
        ];
    }

    /** @throws Throwable */
    private function request(): void
    {
        // we need to use a bypass to avoid the validations when
        // the component is built by soft customization feature.
        if ($this->ignoreValidations) {
            return;
        }

        if (! $this->select) {
            throw new InvalidArgumentException('The [select] parameter must be defined');
        }

        if ($this->request && ! is_array($this->request)) {
            return;
        }

        $this->validate();
    }

    /** @throws Throwable */
    private function validate(): void
    {
        if (! $this->request) {
            throw new InvalidArgumentException('The [request] parameter must be defined');
        }

        if (! isset($this->request['url'])) {
            throw new InvalidArgumentException('The [url] is required in the request array');
        }

        $this->request['method'] ??= 'get';
        $this->request['method'] = strtolower($this->request['method']);

        // We remove search from the request because
        // the search will be attached on the javascript.
        if (isset($this->request['search'])) {
            unset($this->request['search']);
        }

        if (! in_array($this->request['method'], ['get', 'post'])) {
            throw new InvalidArgumentException('The [method] must be get or post');
        }

        if (isset($this->request['params']) && blank($this->request['params'])) {
            throw new InvalidArgumentException('The [params] must be an array and cannot be empty');
        }
    }
}
