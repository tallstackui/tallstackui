<?php

namespace TallStackUi\Foundation\Support\Interactions\Traits;

use Closure;
use Exception;
use InvalidArgumentException;
use TallStackUi\Foundation\Support\Interactions\Dialog;
use TallStackUi\Foundation\Support\Interactions\Toast;

/**
 * @internal
 */
trait DispatchInteraction
{
    /**
     * Whether to dispatch the interaction when flashed.
     */
    protected bool $dispatch = true;

    /**
     * Whether to flash the interaction into session.
     */
    protected bool $flash = false;

    /**
     * Persist the interaction into session to be displayed after redirects.
     *
     * @param  bool  $dispatch  Avoid continuing the dispatch of the interaction.
     */
    public function flash(bool $dispatch = false): self
    {
        $this->flash = true;

        $this->dispatch = $dispatch;

        return $this;
    }

    /**
     * Interact with hooks: `close`, `dismiss` and `timeout`
     *
     * @return $this
     *
     * @throws Exception|InvalidArgumentException
     */
    public function hook(array $hooks): self
    {
        $this->wireable('hook');

        $expected = match (true) {
            $this instanceof Dialog => ['ok', 'close', 'dismiss'],
            $this instanceof Toast => ['close', 'timeout'], // @phpstan-ignore-line
            default => throw new Exception('The interaction hooks is not supported for '.static::class),
        };

        $collect = collect($hooks)->mapWithKeys(fn (array|Closure $hook, string $key): array => [$key => $hook instanceof Closure ? app()->call(fn () => $hook()) : $hook]);

        if ($collect->keys()->diff($expected)->isNotEmpty()) {
            throw new InvalidArgumentException('The interaction hooks for ['.class_basename(static::class).'] must be one of the following: '.implode(', ', $expected));
        }

        $this->data['hooks'] = $collect->toArray();

        return $this;
    }

    /**
     * Dispatch the interaction.
     */
    public function send(): void
    {
        $data = $this->data;

        if (method_exists($this, 'additional')) {
            $data = array_merge($data, $this->additional());
        }

        $event = sprintf('tallstackui:%s', $this->event());

        if ($this->component) {
            $data['component'] = $this->component->getId();

            $this->dispatch && $this->component->dispatch($event, ...$data);
        } else {
            // This else indicates that the sending is taking place via
            // Controller, outside the Livewire scope. So we automatically
            // set the send to flush to make it necessary to manually set it.
            $this->flash();
        }

        if (! $this->flash) {
            return;
        }

        // For some unknown reason the `flash` doesn't work,
        // so we use `put` here and `pull` in the blade file.
        session()->put($event, $data);
    }
}
