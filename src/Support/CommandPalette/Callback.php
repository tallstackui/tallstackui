<?php

namespace TallStackUi\Support\CommandPalette;

use Illuminate\Contracts\Support\Arrayable;

class Callback implements Arrayable
{
    private function __construct(
        private readonly string $type,
        private array $data = [],
        private bool $external = false,
        private bool $navigate = false,
    ) {
        //
    }

    /** Create a callback that dispatches a browser event with the given name. */
    public static function event(string $name): self
    {
        return new self('event', ['name' => $name]);
    }

    /** Create a callback that redirects the browser to the given path. */
    public static function redirect(string $to): self
    {
        return new self('redirect', ['to' => $to]);
    }

    /** Mark the redirect callback to open in a new tab. */
    public function external(): self
    {
        $this->external = true;

        return $this;
    }

    /** Mark the redirect callback to use Livewire.navigate. */
    public function navigate(): self
    {
        $this->navigate = true;

        return $this;
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
            'external' => $this->external,
            'navigate' => $this->navigate,
        ];
    }

    /** Attach parameters to the event callback. */
    public function with(array $params): self
    {
        $this->data['params'] = $params;

        return $this;
    }
}
