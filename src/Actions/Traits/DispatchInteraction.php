<?php

namespace TallStackUi\Actions\Traits;

use Closure;
use InvalidArgumentException;

/**
 * @internal This trait is not meant to be used directly.
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
     */
    public function hook(array $hooks): self
    {
        $expected = ['ok', 'close', 'dismiss', 'timeout'];

        $collect = collect($hooks)->mapWithKeys(fn (array|Closure $hook, string $key): array => [$key => $hook instanceof Closure ? app()->call(fn () => $hook()) : $hook]);

        if ($collect->keys()->diff($expected)->isNotEmpty()) {
            throw new InvalidArgumentException('The interaction hooks must be: close, dismiss, timeout');
        }

        $this->data['hooks'] = $collect->toArray();

        return $this;
    }

    public function send(): void
    {
        $data = $this->data;

        if (method_exists($this, 'additional')) {
            $data = array_merge($data, $this->additional());
        }

        $data['component'] = $this->component->getId();

        $event = sprintf('tallstackui:%s', $this->event());

        if ($this->dispatch) {
            $this->component->dispatch($event, ...$data);
        }

        if (! $this->flash) {
            return;
        }

        // For some unknown reason the `flash` doesn't work,
        // so we use `put` here and `pull` in the blade file.
        session()->put($event, $data);
    }
}
