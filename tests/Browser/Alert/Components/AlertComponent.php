<?php

namespace Tests\Browser\Alert\Components;

use Livewire\Component;

class AlertComponent extends Component
{
    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-alert closeable>Foo bar</x-alert>
        </div>
HTML;
    }
}
