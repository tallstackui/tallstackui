<?php

namespace TallStackUi\View\Personalizations\Providers;

use Exception;
use Illuminate\Support\Facades\View as FacadeView;
use Illuminate\View\View;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Modal;

/**
 * @internal This class is not meant to be used directly.
 */
class ConfigurationProvider
{
    /** @throws Exception */
    public static function resolve(object $component): void
    {
        /** @var string|array $data */
        $data = (match (get_class($component)) {
            Dialog::class => fn () => 'dialog',
            Toast::class => fn () => 'toast',
            Modal::class => fn () => (new self())->modal($component),
            default => throw new Exception("No configurations available for the component: [$component]"),
        })();

        if (is_string($data)) {
            $configuration = config('tallstackui.personalizations.'.$data);

            $data = collect($configuration)
                ->mapWithKeys(fn (string|bool|array $value, string $key) => [$key => $value])
                ->toArray();
        }

        FacadeView::composer($component->render()->name(), fn (View $view) => $view->with('configurations', [...$data]));
    }

    private function modal(Modal $modal): array
    {
        $configuration = config('tallstackui.personalizations.modal');

        $modal->zIndex ??= $configuration['z-index'];
        $modal->size ??= $configuration['size'];
        $modal->blur ??= $configuration['blur'];
        $modal->uncloseable ??= $configuration['uncloseable'];

        $modal->size = match ($modal->size) {
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

        return collect($modal)
            ->only('zIndex', 'size', 'blur', 'uncloseable')
            ->toArray();
    }
}
