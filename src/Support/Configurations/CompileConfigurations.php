<?php

namespace TallStackUi\Support\Configurations;

use Exception;
use TallStackUi\View\Components\Form\Color;
use TallStackUi\View\Components\Form\Select\Styled;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Loading;
use TallStackUi\View\Components\Modal;
use TallStackUi\View\Components\Slide;

/**
 * @internal
 */
class CompileConfigurations
{
    /** @throws Exception */
    public static function of(object $component): ?array
    {
        $class = new self;

        /** @var string|array|null $data */
        $data = (match (true) { // @phpstan-ignore-line
            $component instanceof Color => fn () => $class->color($component),
            $component instanceof Dialog => fn () => Dialog::class,
            $component instanceof Loading => fn () => $class->loading($component),
            $component instanceof Modal => fn () => $class->modal($component),
            $component instanceof Styled => fn () => $class->select($component),
            $component instanceof Slide => fn () => $class->slide($component),
            $component instanceof Toast => fn () => Toast::class,
            default => fn () => null,
        })();

        if (! $data) {
            return null;
        }

        // When the result of $data is a string, then we consult the
        // config file and make a direct mapping, so there is no need
        // to create a method for each component.
        if (is_string($data)) {
            $data = __ts_get_component_configuration($data);
        }

        return $data;
    }

    /**
     * Define the Color component configurations.
     *
     * @throws Exception
     */
    private function color(Color $component): array
    {
        $configuration = __ts_get_component_configuration(Color::class);

        $component->colors ??= $configuration['colors'] ?? [];

        return collect($component)->only('colors')->toArray();
    }

    /**
     * Define the Loading component configurations.
     */
    private function loading(Loading $component): array
    {
        $configuration = __ts_get_component_configuration(Loading::class);

        $component->zIndex ??= $configuration['z-index'] ?? 'z-50';
        $component->overflow ??= $configuration['overflow'] ?? false;
        $component->blur ??= $configuration['blur'] ?? false;
        $component->opacity ??= $configuration['opacity'] ?? true;

        return collect($component)
            ->only(['zIndex', 'overflow', 'blur', 'opacity'])
            ->toArray();
    }

    /**
     * Define the Modal component configurations.
     */
    private function modal(Modal $component): array
    {
        $configuration = __ts_get_component_configuration(Modal::class);

        $component->zIndex ??= $configuration['z-index'] ?? 'z-50';
        $component->overflow ??= $configuration['overflow'] ?? false;
        $component->size ??= $configuration['size'] ?? '2xl';
        $component->blur ??= $configuration['blur'] ?? false;
        $component->persistent ??= $configuration['persistent'] ?? false;
        $component->center ??= $configuration['center'] ?? false;
        $component->scrollable ??= $configuration['scrollable'] ?? false;

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
            'full' => 'max-w-full',
            default => 'sm:max-w-2xl',
        };

        return collect($component)
            ->only([
                'zIndex',
                'overflow',
                'size',
                'blur',
                'persistent',
                'center',
                'scrollable',
                'scrollbar',
            ])
            ->merge(['scrollbar' => $configuration['scrollbar'] ?? null])
            ->toArray();
    }

    private function select(Styled $component): array
    {
        $configuration = __ts_get_component_configuration(Styled::class);

        $component->unfiltered ??= $configuration['unfiltered'] ?? false;

        return collect($component)
            ->only('unfiltered')
            ->toArray();
    }

    /**
     * Define the Slide component configurations.
     */
    private function slide(Slide $component): array
    {
        $configuration = __ts_get_component_configuration(Slide::class);

        $component->zIndex ??= $configuration['z-index'] ?? 'z-50';
        $component->overflow ??= $configuration['overflow'] ?? false;
        $component->size ??= $configuration['size'] ?? 'lg';
        $component->blur ??= $configuration['blur'] ?? false;
        $component->persistent ??= $configuration['persistent'] ?? false;
        $component->left ??= ($configuration['position'] ?? 'right') === 'left';
        $component->top ??= ($configuration['position'] ?? 'right') === 'top';
        $component->bottom ??= ($configuration['position'] ?? 'right') === 'bottom';

        $component->size = match ($component->size) {
            'sm' => $component->bottom || $component->top ? 'h-[24rem] sm:max-h-[12rem]' : 'sm:max-w-sm',
            'md' => $component->bottom || $component->top ? 'h-[28rem] sm:max-h-[16rem]' : 'sm:max-w-md',
            'lg' => $component->bottom || $component->top ? 'h-[32rem] sm:max-h-[20rem]' : 'sm:max-w-lg',
            'xl' => $component->bottom || $component->top ? 'h-[36rem] sm:max-h-[24rem]' : 'sm:max-w-xl',
            '2xl' => $component->bottom || $component->top ? 'h-[40rem] sm:max-h-[28rem]' : 'sm:max-w-2xl',
            '3xl' => $component->bottom || $component->top ? 'h-[48rem] sm:max-h-[32rem]' : 'sm:max-w-3xl',
            '4xl' => $component->bottom || $component->top ? 'h-[56rem] sm:max-h-[32rem]' : 'sm:max-w-4xl',
            '5xl' => $component->bottom || $component->top ? 'h-[64rem] sm:max-h-[42rem]' : 'sm:max-w-5xl',
            '6xl' => $component->bottom || $component->top ? 'h-[72rem] sm:max-h-[48rem]' : 'sm:max-w-6xl',
            '7xl' => $component->bottom || $component->top ? 'h-[80rem] sm:max-h-[56rem]' : 'sm:max-w-7xl',
            'full' => $component->bottom || $component->top ? 'h-full' : 'full',
            default => $component->bottom || $component->top ? 'h-fit sm:max-h-fit' : 'sm:max-w-2xl',
        };

        return collect($component)
            ->only(['zIndex', 'overflow', 'left', 'size', 'blur', 'persistent', 'top', 'bottom'])
            ->toArray();
    }
}
