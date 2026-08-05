<?php

namespace TallStackUi\Support\Configurations;

use Exception;
use Illuminate\Support\Facades\URL;
use TallStackUi\Components\Chart\Component as Chart;
use TallStackUi\Components\CommandPalette\Component as CommandPalette;
use TallStackUi\Components\Dialog\Component as Dialog;
use TallStackUi\Components\Editor\Component as Editor;
use TallStackUi\Components\Form\Autocomplete\Component as Autocomplete;
use TallStackUi\Components\Form\Color\Component as Color;
use TallStackUi\Components\Form\Currency\Component as Currency;
use TallStackUi\Components\Form\Select\Styled\Component as SelectStyled;
use TallStackUi\Components\Gallery\Component as Gallery;
use TallStackUi\Components\Loading\Component as Loading;
use TallStackUi\Components\Modal\Component as Modal;
use TallStackUi\Components\QrCode\Component as QrCode;
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
            $component instanceof Chart => fn () => self::chart($component),
            $component instanceof CommandPalette => fn () => self::commandPalette($component),
            $component instanceof Color => fn () => self::color($component),
            $component instanceof Currency => fn () => self::currency($component),
            $component instanceof Dialog => fn () => Dialog::class,
            $component instanceof Editor => fn () => self::editor($component),
            $component instanceof Gallery => fn () => self::gallery($component),
            $component instanceof Loading => fn () => self::loading($component),
            $component instanceof Modal => fn () => self::modal($component),
            $component instanceof QrCode => fn () => self::qrCode($component),
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
     * Define the Chart component configurations.
     *
     * @throws Exception
     */
    private static function chart(Chart $component): array
    {
        $configuration = __ts_get_component_configuration(Chart::class);

        $component->height ??= $configuration['height'] ?? 64;
        $component->legend ??= $configuration['legend'] ?? false;
        $component->tooltip ??= $configuration['tooltip'] ?? false;
        $component->markers ??= $configuration['markers'] ?? false;

        // validate() has already run, so a global default would slip past the
        // rule that rejects a labelled axis on a type that has none.
        $component->grid ??= in_array($component->type, ['pie', 'donut'], true)
            ? false
            : ($configuration['grid'] ?? false);

        return ['height' => $component->height];
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

    private static function currency(Currency $component): array
    {
        $configuration = __ts_get_component_configuration(Currency::class);

        $component->mutate ??= $configuration['mutate'] ?? false;
        $component->decimal ??= $configuration['decimal'] ?? false;

        return ['mutate' => $component->mutate, 'decimal' => $component->decimal];
    }

    /**
     * Define the Editor component configurations.
     *
     * @throws Exception
     */
    private static function editor(Editor $component): array
    {
        $configuration = __ts_get_component_configuration(Editor::class);

        $component->markdown ??= $configuration['markdown'];
        $component->toolbar ??= $configuration['toolbar'];
        $component->counters ??= $configuration['counters'];
        $component->minHeight ??= $configuration['min_height'];
        $component->maxHeight ??= $configuration['max_height'];
        $component->uploadMimes ??= $configuration['upload']['mimes'];
        $component->uploadMaxSize ??= $configuration['upload']['max_size'];
        $component->placeholder ??= trans('ts-ui::messages.editor.placeholder');

        return [
            'markdown' => $component->markdown,
            'toolbar' => $component->toolbar,
            'counters' => $component->counters,
            'placeholder' => $component->placeholder,
            'heights' => [
                'min' => $component->minHeight,
                'max' => $component->maxHeight,
            ],
            'sanitization' => $configuration['sanitization'],
        ];
    }

    /**
     * Define the Gallery component configurations.
     *
     * @throws Exception
     */
    private static function gallery(Gallery $component): array
    {
        $configuration = __ts_get_component_configuration(Gallery::class);

        $layout = match (true) {
            (bool) $component->masonry => 'masonry',
            (bool) $component->feature => 'feature',
            default => 'grid',
        };

        $component->columns ??= $configuration['columns'] ?? 3;
        $component->limit ??= $configuration['limit'] ?? 7;
        $component->thumbnails ??= $configuration['thumbnails'] ?? 'bottom';

        // The feature layout uses the ratio to shape the cover image,
        // where a wide default reads better than the grid's square.
        $component->ratio ??= $configuration['ratio'] ?? ($layout === 'feature' ? 'video' : 'square');

        return [
            'layout' => $layout,
            'thumbnails' => $component->thumbnails,
            'columns' => $layout === 'masonry'
                ? match ($component->columns) {
                    2 => 'columns-1 sm:columns-2',
                    4 => 'columns-2 sm:columns-3 lg:columns-4',
                    5 => 'columns-2 sm:columns-3 lg:columns-5',
                    6 => 'columns-2 sm:columns-4 lg:columns-6',
                    default => 'columns-2 sm:columns-3',
                }
                : match ($component->columns) {
                    2 => 'grid-cols-1 sm:grid-cols-2',
                    4 => 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4',
                    5 => 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-5',
                    6 => 'grid-cols-2 sm:grid-cols-4 lg:grid-cols-6',
                    default => 'grid-cols-2 sm:grid-cols-3',
                },
            'ratio' => match ($component->ratio) {
                'video' => 'aspect-video',
                'portrait' => 'aspect-[3/4]',
                default => 'aspect-square',
            },
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
            'position' => match (true) {
                $component->center === true => 'center',
                is_string($component->center) => 'center-'.$component->center,
                default => 'top',
            },
            'scrollable' => $component->scrollable,
            'scrollbar' => $configuration['scrollbar'] ?? null,
        ];
    }

    /**
     * Define the QrCode component configurations.
     *
     * @throws Exception
     */
    private static function qrCode(QrCode $component): array
    {
        $configuration = __ts_get_component_configuration(QrCode::class);

        $component->size ??= $configuration['size'] ?? 'md';

        return ['pixels' => $configuration['pixels'] ?? 1024];
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
