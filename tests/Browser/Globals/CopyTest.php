<?php

use Livewire\Component;
use Livewire\Livewire;

it('exposes the $tsui.copy global', function () {
    $browser = Livewire::visit(new class extends Component
    {
        public function render(): string
        {
            return <<<'HTML'
            <div>Ready</div>
            HTML;
        }
    })->waitForText('Ready');

    expect($browser->script('return typeof window.$tsui.copy')[0])->toBe('function');
});

it('can copy arbitrary text and dispatch the ts-ui:copy event', function () {
    Livewire::visit(new class extends Component
    {
        public function render(): string
        {
            return <<<'HTML'
            <div
                x-data="{ result: '', copied: '' }"
                x-on:ts-ui:copy.window="copied = $event.detail.text"
            >
                <button
                    type="button"
                    dusk="trigger"
                    x-on:click="result = (await window.$tsui.copy('TallStackUI is awesome')) ? 'copied' : 'failed'"
                >
                    Copy
                </button>

                <span dusk="result" x-text="result"></span>
                <span dusk="event" x-text="copied"></span>
            </div>
            HTML;
        }
    })
        ->waitForText('Copy')
        ->click('@trigger')
        ->waitForTextIn('@result', 'copied')
        ->assertSeeIn('@result', 'copied')
        ->assertSeeIn('@event', 'TallStackUI is awesome');
});
