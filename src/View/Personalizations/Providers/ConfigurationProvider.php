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
    private const SQUARES = [
        'tallstack-ui::components.alert',
        'tallstack-ui::components.card',
        'tallstack-ui::components.dropdown.dropdown',
        'tallstack-ui::components.dropdown.items',
        'tallstack-ui::components.errors',
        'tallstack-ui::components.form.input',
        'tallstack-ui::components.form.textarea',
        'tallstack-ui::components.form.password',
        'tallstack-ui::components.form.checkbox',
        'tallstack-ui::components.interaction.dialog',
        'tallstack-ui::components.interaction.toast',
        'tallstack-ui::components.modal',
        'tallstack-ui::components.select.select',
        'tallstack-ui::components.tab.tab',
        'tallstack-ui::components.tab.items',
        'tallstack-ui::components.wrapper.select',
    ];

    /** @throws Exception */
    public static function resolve(object $component = null): void
    {
        if (! $component) {
            $configuration = config('tallstackui.personalizations.general');

            collect(self::SQUARES)
                ->each(fn (string $view) => FacadeView::composer($view, fn (View $view) => $view->with('configurations', [...$configuration])));

            return;
        }

        $method = match (get_class($component)) {
            Dialog::class => 'dialog',
            Toast::class => 'toast',
            default => throw new Exception('No configuration available for this component'),
        };

        FacadeView::composer($component->render()->name(), fn (View $view) => $view->with('configurations', [...(new self())->$method()]));
    }

    private function dialog(): array
    {
        $configuration = config('tallstackui.personalizations.dialog');

        return collect($configuration)
            ->mapWithKeys(fn (string|bool|array $value, string $key) => [$key => $value])
            ->toArray();
    }

    private function toast(): array
    {
        $configuration = config('tallstackui.personalizations.toast');

        return collect($configuration)
            ->mapWithKeys(fn (string|bool|array $value, string $key) => [$key => $value])
            ->toArray();
    }
}
