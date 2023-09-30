<?php

namespace Tests\Browser\Alert\Components;

use Livewire\Component;
use TasteUi\Contracts\Personalizable;
use TasteUi\Facades\TasteUi;

class Personalize implements Personalizable
{
    public function __invoke(array $data): string
    {
        return 'rounded-md p-6 bg-red-500';
    }
}

class AlertCustomPersonalizedComponent extends Component
{
    public function render(): string
    {
        TasteUi::personalize('alert')
            ->block('base', new Personalize());

        return <<<'HTML'
        <div>
            <x-alert>Foo bar</x-alert>
        </div>
HTML;
    }
}
