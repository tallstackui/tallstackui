<?php

namespace TallStackUi\Components\Modal;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use TallStackUi\Traits\Interactions;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_dispatch_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public string $target = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="target">{{ $target }}</p>

                    <x-modal wire title="Modal" x-on:open="$wire.set('target', 'Opened')" x-on:close="$wire.set('target', 'Closed')">
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" wire:click="$toggle('modal')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->assertSee('Foo bar')
            ->assertSeeIn('@target', 'Opened')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/div/div[1]/button')
            ->waitUntilMissingText('Foo bar')
            ->assertDontSee('Foo bar')
            ->assertSeeIn('@target', 'Closed');
    }

    #[Test]
    public function can_open(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal wire>
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" wire:click="$toggle('modal')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->assertSee('Foo bar');
    }

    #[Test]
    public function can_open_and_see_footer(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal wire title="Bar baz">
                        Foo bar
                        <x-slot:footer>
                            Lorem                
                        </x-slot:footer>
                    </x-modal>
                
                    <x-button dusk="open" wire:click="$toggle('modal')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->assertDontSee('Bar baz')
            ->click('@open')
            ->waitForText(['Foo bar', 'Lorem'])
            ->assertSee('Foo bar')
            ->assertSee('Lorem');
    }

    #[Test]
    public function can_open_and_see_title(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal wire title="Bar baz">
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" wire:click="$toggle('modal')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->assertDontSee('Bar baz')
            ->click('@open')
            ->waitForText(['Foo bar', 'Bar baz'])
            ->assertSee('Foo bar')
            ->assertSee('Bar baz');
    }

    #[Test]
    public function can_open_using_different_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $test = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal wire="test">
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" wire:click="$toggle('test')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->assertSee('Foo bar');
    }

    #[Test]
    public function can_open_using_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal id="test">
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" x-on:click="$modalOpen('test')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->assertSee('Foo bar');
    }

    #[Test]
    public function cannot_close_when_modal_is_persistent(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal id="persistent" persistent>
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" x-on:click="$modalOpen('persistent')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->clickAtPoint(350, 350)
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div')
            ->waitForText('Foo bar')
            ->assertSee('Foo bar');
    }

    #[Test]
    public function closing_dialog_via_ok_button_keeps_modal_open(): void
    {
        Livewire::visit(new class extends Component
        {
            use Interactions;

            public function triggerDialog(): void
            {
                $this->dialog()
                    ->success('Dialog Title', 'Dialog description')
                    ->send();
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-modal title="Modal Content" id="test-modal" z-index="z-40">
                        Modal body
                        <x-button dusk="trigger-dialog" wire:click="triggerDialog">Trigger Dialog</x-button>
                    </x-modal>

                    <x-button dusk="open-modal" x-on:click="$modalOpen('test-modal')">Open Modal</x-button>
                </div>
                HTML;
            }
        })
            ->assertDontSee('Modal body')
            ->click('@open-modal')
            ->waitForText('Modal body')
            ->assertSee('Modal body')
            ->waitForLivewire()->click('@trigger-dialog')
            ->waitForText('Dialog Title')
            ->assertSee('Dialog Title')
            ->click('@tallstackui_dialog_confirmation')
            ->waitUntilMissingText('Dialog Title')
            ->assertDontSee('Dialog Title')
            ->assertSee('Modal body');
    }

    #[Test]
    public function closing_toast_does_not_affect_modal(): void
    {
        Livewire::visit(new class extends Component
        {
            use Interactions;

            public function triggerToast(): void
            {
                $this->toast()->success('Toast Title', 'Toast description')->timeout(3)->send();
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-modal title="Modal Content" id="test-modal">
                        Modal body
                        <x-button dusk="trigger-toast" wire:click="triggerToast">Trigger Toast</x-button>
                    </x-modal>

                    <x-button dusk="open-modal" x-on:click="$modalOpen('test-modal')">Open Modal</x-button>
                </div>
                HTML;
            }
        })
            ->assertDontSee('Modal body')
            ->click('@open-modal')
            ->waitForText('Modal body')
            ->assertSee('Modal body')
            ->waitForLivewire()->click('@trigger-toast')
            ->waitForText('Toast Title')
            ->assertSee('Toast Title')
            ->assertSee('Modal body')
            ->waitUntilMissingText('Toast Title')
            ->assertSee('Modal body');
    }

    #[Test]
    public function escape_closes_only_slide_when_modal_and_slide_are_open(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-modal wire title="Modal Content" z-index="z-40">
                        Modal body
                        <x-button dusk="open-slide" x-on:click="$slideOpen('test-slide')">Open Slide</x-button>
                    </x-modal>

                    <x-slide id="test-slide" title="Slide Content" z-index="z-50">
                        Slide body
                    </x-slide>

                    <x-button dusk="open-modal" wire:click="$toggle('modal')">Open Modal</x-button>
                </div>
                HTML;
            }
        })
            ->assertDontSee('Modal body')
            ->assertDontSee('Slide body')
            ->click('@open-modal')
            ->waitForText('Modal body')
            ->assertSee('Modal body')
            ->click('@open-slide')
            ->waitForText('Slide body')
            ->assertSee('Slide body')
            ->assertSee('Modal body')
            ->keys('', '{escape}')
            ->waitUntilMissingText('Slide body')
            ->assertDontSee('Slide body')
            ->assertSee('Modal body');
    }
}
