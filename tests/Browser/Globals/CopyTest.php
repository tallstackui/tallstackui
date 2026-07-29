<?php

namespace Tests\Browser\Globals;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class CopyTest extends BrowserTestCase
{
    #[Test]
    public function can_copy_arbitrary_text_and_dispatch_the_copy_event(): void
    {
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
    }

    #[Test]
    public function exposes_the_copy_global(): void
    {
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
    }
}
