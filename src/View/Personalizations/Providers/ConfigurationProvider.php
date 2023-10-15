<?php

namespace TallStackUi\View\Personalizations\Providers;

use Exception;
use Illuminate\Support\Facades\View as FacadeView;
use Illuminate\View\View;
use TallStackUi\View\Components\Form\Input;
use TallStackUi\View\Components\Form\Password;
use TallStackUi\View\Components\Form\Textarea;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Tab\Items;
use TallStackUi\View\Components\Tab\Tab;

class ConfigurationProvider
{
    /** @throws Exception */
    public static function resolve(object $component): void
    {
        $method = match (get_class($component)) {
            Input::class, Password::class, Textarea::class => 'input',
            Tab::class, Items::class => 'tab',
            Dialog::class => 'dialog',
            Toast::class => 'toast',
            default => throw new Exception('No configuration available for this component'),
        };

        FacadeView::composer($component->render()->name(), fn (View $view) => $view->with('configurations', [...(new self())->$method($component)]));
    }

    private function dialog(Dialog $dialog): array
    {
        $configuration = config('tallstackui.personalizations.dialog');

        ValidationProvider::resolve($dialog, ...$configuration);

        return collect($configuration)
            ->mapWithKeys(fn (mixed $value, string $key) => [$key => $value])->toArray();
    }

    private function input(): array
    {
        $configuration = config('tallstackui.personalizations.input');

        return collect($configuration)
            ->mapWithKeys(fn (mixed $value, string $key) => [$key => $value])->toArray();
    }

    private function tab(): array
    {
        $configuration = config('tallstackui.personalizations.tab');

        return collect($configuration)
            ->mapWithKeys(fn (mixed $value, string $key) => [$key => $value])->toArray();
    }

    private function toast(Toast $toast): array
    {
        $configuration = config('tallstackui.personalizations.toast');

        ValidationProvider::resolve($toast, ...$configuration);

        return collect($configuration)
            ->mapWithKeys(fn (mixed $value, string $key) => [$key => $value])->toArray();
    }
}
