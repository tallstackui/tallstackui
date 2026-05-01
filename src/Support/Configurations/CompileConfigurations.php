<?php

namespace TallStackUi\Support\Configurations;

use Exception;
use Illuminate\Support\Facades\URL;
use TallStackUi\Components\CommandPalette\Component as CommandPalette;
use TallStackUi\Components\Dialog\Component as Dialog;
use TallStackUi\Components\Form\Autocomplete\Component as Autocomplete;
use TallStackUi\Components\Form\Color\Component as Color;
use TallStackUi\Components\Form\Select\Styled\Component as SelectStyled;
use TallStackUi\Components\Loading\Component as Loading;
use TallStackUi\Components\Modal\Component as Modal;
use TallStackUi\Components\Slide\Component as Slide;
use TallStackUi\Components\Toast\Component as Toast;

/**
 * @internal
 */
class CompileConfigurations
{
    /** @throws Exception */
    public static function of(object $component): ?array
    {
        /** @var string|array|null $data */
        $data = (match (true) { // @phpstan-ignore-line
            $component instanceof Autocomplete => fn () => self::autocomplete($component),
            $component instanceof CommandPalette => fn () => self::commandPalette($component),
            $component instanceof Color => fn () => self::color($component),
            $component instanceof Dialog => fn () => Dialog::class,
            $component instanceof Loading => fn () => self::loading($component),
            $component instanceof Modal => fn () => self::modal($component),
            $component instanceof SelectStyled => fn () => self::select($component),
            $component instanceof Slide => fn () => self::slide($component),
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
     * Define the Autocomplete component configurations.
     *
     * @throws Exception
     */
    private static function autocomplete(Autocomplete $component): array
    {
        $configuration = __ts_get_component_configuration(Autocomplete::class);

        $component->strict ??= $configuration['strict'] ?? false;

        return ['strict' => $component->strict];
    }

    /**
     * Define the Color component configurations.
     *
     * @throws Exception
     */
    private static function color(Color $component): array
    {
        $configuration = __ts_get_component_configuration(Color::class);

        $component->colors ??= $configuration['colors'] ?? [];

        return ['colors' => $component->colors];
    }

    private static function commandPalette(CommandPalette $component): array
    {
        $configuration = __ts_get_component_configuration(CommandPalette::class);

        $actionable = $configuration['actionable'] ?? null;

        $component->recycle ??= $configuration['recycle'] ?? true;
        $component->shortcut ??= $configuration['shortcut'] ?? 'ctrl.k';
        $component->centered ??= $configuration['centered'] ?? false;
        $component->overlay ??= $configuration['overlay'] ?? true;

        return [
            'url' => $actionable ? URL::signedRoute('tallstackui.command-palette.action') : null,
            'zIndex' => $configuration['z-index'] ?? 'z-50',
            'blur' => $configuration['blur'] ?? false,
            'overflow' => $configuration['overflow'] ?? false,
            'shortcut' => $component->shortcut,
            'recycle' => $component->recycle,
            'elements' => $configuration['elements'] ?? true,
            'scrollbar' => $configuration['scrollbar'] ?? true,
            'centered' => $component->centered,
            'overlay' => $component->overlay,
        ];
    }

    /**
     * Define the Loading component configurations.
     */
    private static function loading(Loading $component): array
    {
        $configuration = __ts_get_component_configuration(Loading::class);

        $component->zIndex ??= $configuration['z-index'] ?? 'z-50';
        $component->overflow ??= $configuration['overflow'] ?? false;
        $component->blur ??= $configuration['blur'] ?? false;
        $component->opacity ??= $configuration['opacity'] ?? true;

        return [
            'zIndex' => $component->zIndex,
            'overflow' => $component->overflow,
            'blur' => $component->blur,
            'opacity' => $component->opacity,
        ];
    }

    /**
     * Define the Modal component configurations.
     */
    private static function modal(Modal $component): array
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

        return [
            'zIndex' => $component->zIndex,
            'overflow' => $component->overflow,
            'size' => $component->size,
            'blur' => $component->blur,
            'persistent' => $component->persistent,
            'center' => $component->center,
            'scrollable' => $component->scrollable,
            'scrollbar' => $configuration['scrollbar'] ?? null,
        ];
    }

    private static function select(SelectStyled $component): array
    {
        $configuration = __ts_get_component_configuration(SelectStyled::class);

        $component->recycle ??= $configuration['recycle'] ?? false;
        $component->unfiltered ??= $configuration['unfiltered'] ?? false;

        return ['recycle' => $component->recycle, 'unfiltered' => $component->unfiltered];
    }

    /**
     * Define the Slide component configurations.
     */
    private static function slide(Slide $component): array
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

        return [
            'zIndex' => $component->zIndex,
            'overflow' => $component->overflow,
            'left' => $component->left,
            'size' => $component->size,
            'blur' => $component->blur,
            'persistent' => $component->persistent,
            'top' => $component->top,
            'bottom' => $component->bottom,
        ];
    }
}
