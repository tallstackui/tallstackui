<?php

namespace TallStackUi\Interactions\Traits;

use Closure;
use Exception;
use Illuminate\Support\Str;
use InvalidArgumentException;
use TallStackUi\Interactions\Dialog;
use TallStackUi\Interactions\Toast;

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

        $data = array_merge($data, $this->additional());

        $event = sprintf('ts-ui:%s', $this->event());

        $data['id'] ??= (string) Str::uuid();

        if ($this->component) {
            $data['reference'] = $this->component->getId();

            if ($this->dispatch) {
                $this->component->dispatch($event, ...$data);
            }
        } else {
            // This else indicates that the sending is taking place via
            // Controller, outside the Livewire scope. So we automatically
            // set the sending to flush to make it necessary to manually set it.
            $this->flash();
        }

        if (! $this->flash) {
            return;
        }

        session()->put($event, $data);
    }
}
