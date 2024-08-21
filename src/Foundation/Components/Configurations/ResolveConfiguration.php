<?php

namespace TallStackUi\Foundation\Components\Configurations;

use Exception;
use TallStackUi\View\Components\Form\Color;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Loading;
use TallStackUi\View\Components\Modal;
use TallStackUi\View\Components\Slide;

/**
 * @internal
 */
class ResolveConfiguration
{
    /** @throws Exception */
    public static function of(object $component): ?array
    {
        $class = new self;

        /** @var string|array|null $data */
        $data = (match (true) {
            $component instanceof Color => fn () => $class->color($component),
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

        // When the result of $data is a string, then we consult the
        // config file and make a direct mapping so there is no need
        // to create a method for each component.
        if (is_string($data)) {
            $data = __ts_configuration('settings.'.$data)
                ->mapWithKeys(fn (string|bool|array $value, string $key) => [$key => $value])
                ->toArray();
        }

        return $data;
    }

    /**
     * Defines the Color component configurations.
     */
    private function color(Color $component): array
    {
        $configuration = __ts_configuration('settings.form.color');

        $component->colors ??= $configuration->get('colors') ?? [];

        return collect($component)->only('colors')->toArray();
    }

    /**
     * Defines the Loading component configurations.
     */
    private function loading(Loading $component): array
    {
        $configuration = __ts_configuration('settings.loading');

        $component->zIndex ??= $configuration->get('z-index', 'z-50');
        $component->overflow ??= $configuration->get('overflow', false);
        $component->blur ??= $configuration->get('blur', false);
        $component->opacity ??= $configuration->get('opacity', true);

        return collect($component)
            ->only(['zIndex', 'overflow', 'blur', 'opacity'])
            ->toArray();
    }

    /**
     * Defines the Modal component configurations.
     */
    private function modal(Modal $component): array
    {
        $configuration = __ts_configuration('settings.modal');

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

    /**
     * Defines the Slide component configurations.
     */
    private function slide(Slide $component): array
    {
        $configuration = __ts_configuration('settings.slide');

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
