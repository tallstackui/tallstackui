<?php

namespace TallStackUi\Foundation;

use Exception;
use Illuminate\Support\Collection;
use TallStackUi\View\Components\Form\Color;
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
        $class = new self;

        /** @var string|array $data */
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

        if (is_string($data)) {
            $data = $class->config($data)
                ->mapWithKeys(fn (string|bool|array $value, string $key) => [$key => $value])
                ->toArray();
        }

        return $data;
    }

    private function color(Color $component): array
    {
        $configuration = $this->config('form.color');

        $component->colors ??= $configuration->get('colors') ?? [];

        return collect($component)->only('colors')->toArray();
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
        $component->top ??= $configuration->get('position', 'right') === 'top';
        $component->bottom ??= $configuration->get('position', 'right') === 'bottom';

        $orientation = ($component->bottom || $component->top) ? 'h' : 'w';
        
        $component->size = match ($component->size) {
            'sm' => ($orientation === 'w') ? 'sm:max-w-sm'     : 'h-[24rem] sm:max-h-[12rem]', 
            'md' => ($orientation === 'w') ? 'sm:max-w-md'     : 'h-[28rem] sm:max-h-[16rem]',
            'lg' => ($orientation === 'w') ? 'sm:max-w-lg'     : 'h-[32rem] sm:max-h-[20rem]',
            'xl' => ($orientation === 'w') ? 'sm:max-w-xl'     : 'h-[36rem] sm:max-h-[24rem]',
            '2xl' => ($orientation === 'w') ? 'sm:max-w-2xl'   : 'h-[40rem] sm:max-h-[28rem]',
            '3xl' => ($orientation === 'w') ? 'sm:max-w-3xl'   : 'h-[48rem] sm:max-h-[32rem]',
            '4xl' => ($orientation === 'w') ? 'sm:max-w-4xl'   : 'h-[56rem] sm:max-h-[32rem]',
            '5xl' => ($orientation === 'w') ? 'sm:max-w-5xl'   : 'h-[64rem] sm:max-h-[42rem]',
            '6xl' => ($orientation === 'w') ? 'sm:max-w-6xl'   : 'h-[72rem] sm:max-h-[48rem]',
            '7xl' => ($orientation === 'w') ? 'sm:max-w-7xl'   : 'h-[80rem] sm:max-h-[56rem]',
            'full' => ($orientation === 'w') ? 'full'          : 'h-full',
            default => ($orientation === 'w') ? 'sm:max-w-2xl' : 'h-fit sm:max-h-fit',
        };

        return collect($component)
            ->only(['zIndex', 'overflow', 'left', 'size', 'blur', 'persistent', 'top', 'bottom'])
            ->toArray();
    }
}
