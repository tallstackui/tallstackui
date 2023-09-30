<?php

namespace Tests\Browser\Alert\Components;

use Livewire\Component;
use TasteUi\Facades\TasteUi;

class AlertCommonPersonalizedComponent extends Component
{
    public function render(): string
    {
        TasteUi::personalize('alert')
            ->block('base', fn () => 'rounded-md p-6 bg-red-500');

        return <<<'HTML'
        <div>
            <x-alert>Foo bar</x-alert>
        </div>
HTML;
    }
}
