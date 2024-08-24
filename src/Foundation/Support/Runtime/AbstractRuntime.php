<?php

namespace TallStackUi\Foundation\Support\Runtime;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\ViewErrorBag;
use Livewire\Component;
use TallStackUi\Foundation\Support\Blade\BindProperty;

abstract class AbstractRuntime
{
    public function __construct(
        protected array $data,
        protected readonly object $component,
        protected readonly ?Component $livewire = null,
        protected readonly ?ViewErrorBag $errors = null
    ) {
        //
    }

    /**
     * Shortcut to retrieve the bind data ready to use as a collection.
     *
     * @throws Exception
     */
    public function bind(): Collection
    {
        return app(BindProperty::class, [
            'attributes' => $this->data['attributes'],
            'errors' => $this->errors,
            'invalidate' => $this->data['invalidate'] ?? false,
            // Livewire here is a boolean to check if the
            // component is being used within a Livewire context.
            'livewire' => $this->livewire !== null,
        ])->toCollection();
    }

    /**
     * Determine the runtime properties for the component.
     */
    abstract public function runtime(): array;

    /**
     * Get data from $this->data using data_get when $key is set or return the whole data as a collection.
     */
    protected function data(?string $key = null, mixed $default = null): mixed
    {
        if ($key) {
            return data_get($this->data, $key, $default);
        }

        return collect($this->data);
    }
}
