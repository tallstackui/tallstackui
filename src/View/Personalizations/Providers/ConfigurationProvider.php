<?php

namespace TallStackUi\View\Personalizations\Providers;

use Exception;
use Illuminate\Support\Facades\View as FacadeView;
use Illuminate\View\View;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;

/**
 * @internal This class is not meant to be used directly.
 */
class ConfigurationProvider
{
    /** @throws Exception */
    public static function resolve(object $component): void
    {
        $index = match (get_class($component)) {
            Dialog::class => 'dialog',
            Toast::class => 'toast',
            default => throw new Exception("No configurations available for the component: [$component]"),
        };

        $configuration = config('tallstackui.personalizations.' . $index);

        FacadeView::composer($component->render()->name(), fn (View $view) => $view->with('configurations', [
            ...collect($configuration)
                ->mapWithKeys(fn (string|bool|array $value, string $key) => [$key => $value])
                ->toArray()
        ]));
    }
}
