<?php

namespace TasteUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use InvalidArgumentException;
use Throwable;

class Searchable extends Styled
{
    /** @throws Throwable */
    public function __construct(
        public string|array|null $request = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $multiple = false,
        public ?string $select = null,
        public ?array $selectable = [],
        private readonly bool $skipValidations = false,
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
        return view('taste-ui::components.select.searchable', [
            'placeholder' => __('taste-ui::messages.select.placeholder'),
        ]);
    }

    public function customization(): array
    {
        return [
            ...$this->tasteUiClasses(),
        ];
    }

    public function tasteUiClasses(): array
    {
        return [
            'multiple' => 'inline-flex items-center rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 space-x-1',
            'icon' => 'h-4 w-4 text-gray-700 transition hover:text-red-500',
        ];
    }

    /** @throws Throwable */
    private function request(): void
    {
        if ($this->skipValidations) {
            return;
        }

        throw_if(
            ! $this->select,
            new InvalidArgumentException('The [select] parameter must be defined.')
        );

        if (! is_array($this->request)) {
            return;
        }

        $this->validate();
    }

    /** @throws Throwable */
    private function validate(): void
    {
        throw_unless(
            isset($this->request['url']),
            new InvalidArgumentException('The key: [url] is required in the request array.')
        );

        $this->request['method'] = isset($this->request['method']) ? strtolower($this->request['method']) : 'get';

        // We remove search from the request because
        // the search will be attached on the javascript.
        if (isset($this->request['search'])) {
            unset($this->request['search']);
        }

        throw_unless(
            in_array($this->request['method'], ['get', 'post']),
            new InvalidArgumentException('The key: [method] must be get or post.')
        );

        throw_if(
            isset($this->request['params']) &&
            (empty($this->request['params']) || ! is_array($this->request['params'])),
            new InvalidArgumentException('The key: [params] must be an array.')
        );
    }
}
