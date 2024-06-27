<?php

namespace TallStackUi\Foundation;

use Exception;
use Illuminate\Support\Collection;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Loading;
use TallStackUi\View\Components\Modal;
use TallStackUi\View\Components\Slide;

/**
 * @internal This class is not meant to be used directly.
 */
class ResolveConfiguration
{
    /** @throws Exception */
    public static function from(object $component): ?array
    {
        $class = new self();

        /** @var string|array $data */
        $data = (match (true) {
            $component instanceof Dialog => fn () => 'dialog',
            $component instanceof Loading => fn () => $class->loading($component),
            $component instanceof Modal => fn () => $class->modal($component),
            $component instanceof Slide => fn () => $class->slide($component),
            $component instanceof Toast => fn () => 'toast',
            default => fn () => null,
        })();

        if (! $data) {
            return null;
        }

        if (is_string($data)) {
            $data = $class->config($data)
                ->mapWithKeys(fn (string|bool|array $value, string $key) => [$key => $value])
                ->toArray();
        }

        return $data;
    }

    private function config(string $index): Collection
    {
        return collect(config('tallstackui.settings.'.$index));
    }

    private function loading(Loading $component): array
    {
        $configuration = $this->config('loading');

        $component->zIndex ??= $configuration->get('z-index', 'z-50');
        $component->overflow ??= $configuration->get('overflow', false);
        $component->blur ??= $configuration->get('blur', false);
        $component->opacity ??= $configuration->get('opacity', true);

        return collect($component)
            ->only(['zIndex', 'overflow', 'blur', 'opacity'])
            ->toArray();
    }

    private function modal(Modal $component): array
    {
        $configuration = $this->config('modal');

        $component->zIndex ??= $configuration->get('z-index', 'z-50');
        $component->overflow ??= $configuration->get('overflow', false);
        $component->size ??= $configuration->get('size', '2xl');
        $component->blur ??= $configuration->get('blur', false);
        $component->persistent ??= $configuration->get('persistent', false);
        $component->center ??= $configuration->get('center', false);

        $component->size = match ($component->size) {
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            '6xl' => 'sm:max-w-6xl',
            '7xl' => 'sm:max-w-7xl',
            default => 'sm:max-w-2xl',
        };

        return collect($component)
            ->only(['zIndex', 'overflow', 'size', 'blur', 'persistent', 'center'])
            ->toArray();
    }

    private function slide(Slide $component): array
    {
        $configuration = $this->config('slide');

        $component->zIndex ??= $configuration->get('z-index', 'z-50');
        $component->overflow ??= $configuration->get('overflow', false);
        $component->size ??= $configuration->get('size', 'lg');
        $component->blur ??= $configuration->get('blur', false);
        $component->persistent ??= $configuration->get('persistent', false);
        $component->left ??= $configuration->get('position', 'right') === 'left';

        $component->size = match ($component->size) {
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            '6xl' => 'sm:max-w-6xl',
            '7xl' => 'sm:max-w-7xl',
            'full' => 'full',
            default => 'sm:max-w-2xl',
        };

        return collect($component)
            ->only(['zIndex', 'overflow', 'left', 'size', 'blur', 'persistent'])
            ->toArray();
    }
}
