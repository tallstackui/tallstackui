<?php

namespace TasteUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use InvalidArgumentException;
use Throwable;

class Searchable extends Styled
{
    /** @throws Throwable */
    public function __construct(
        public string|array $request,
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $multiple = false,
        public ?string $select = null,
        public ?array $selectable = [],
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

    /** @throws Throwable */
    private function request(): void
    {
        if (! is_array($this->request)) {
            return;
        }

        $this->validate();
    }

    /** @throws Throwable */
    private function validate(): void
    {
        $keys = [
            'url',
            'method',
        ];

        foreach ($keys as $key) {
            throw_unless(
                array_key_exists($key, $this->request),
                new InvalidArgumentException("The key: [{$key}] is required in the request array.")
            );
        }

        if (isset($this->request['method'])) {
            $this->request['method'] = strtolower($this->request['method']);
        }

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
