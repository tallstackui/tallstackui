<?php

namespace TallStackUi\Components\Clipboard;

use Facebook\WebDriver\WebDriverKeys;
use Laravel\Dusk\OperatingSystem;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_copy_when_icon(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-clipboard text="e4da3b7fbbce2345d7772b0674a318d5" icon />
                    <input dusk="paste">
                </div>
            HTML;
            }
        })
            ->assertDontSee('Your API')
            ->click('@tallstackui_clipboard_icon_copy')
            ->keys('@paste', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'v'])
            ->assertInputValue('@paste', 'e4da3b7fbbce2345d7772b0674a318d5');
    }

    #[Test]
    public function can_copy_when_input(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-clipboard label="Your API" text="c4ca4238a0b923820dcc509a6f75849b" />
                    <input dusk="paste">
                </div>
            HTML;
            }
        })
            ->assertSee('Your API')
            ->click('@tallstackui_clipboard_input_copy')
            ->keys('@paste', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'v'])
            ->assertInputValue('@paste', 'c4ca4238a0b923820dcc509a6f75849b');
    }

    #[Test]
    public function can_copy_when_inside_modal(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-modal wire title="Clipboard in Modal">
                        <x-clipboard text="5f4dcc3b5aa765d61d8327deb882cf99" />
                    </x-modal>

                    <x-button dusk="open-modal" wire:click="$toggle('modal')">Open Modal</x-button>

                    <input dusk="paste">
                </div>
                HTML;
            }
        })
            ->assertSee('Open Modal')
            ->assertDontSee('Clipboard in Modal')
            ->click('@open-modal')
            ->waitForText('Clipboard in Modal')
            ->assertSee('Clipboard in Modal')
            ->click('@tallstackui_clipboard_input_copy')
            ->keys('@paste', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'v'])
            ->assertInputValue('@paste', '5f4dcc3b5aa765d61d8327deb882cf99');
    }

    #[Test]
    public function can_use_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $copied = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="copied">{{ $copied }}</p>

                    <x-clipboard label="Your API" 
                                 text="c4ca4238a0b923820dcc509a6f75849b" 
                                 x-on:copy="$wire.set('copied', $event.detail.text)" />
                </div>
            HTML;
            }
        })
            ->assertSee('Your API')
            ->assertDontSeeIn('@copied', 'c4ca4238a0b923820dcc509a6f75849b')
            ->click('@tallstackui_clipboard_input_copy')
            ->waitForTextIn('@copied', 'c4ca4238a0b923820dcc509a6f75849b')
            ->assertSeeIn('@copied', 'c4ca4238a0b923820dcc509a6f75849b');
    }

    #[Test]
    public function changing_value_externally_continue_providing_ability_to_copy(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $name = 'AJ';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-clipboard label="Your API" :text="$name" />
                    
                    <button dusk="change" wire:click="$set('name', 'FooBarBazBah')">Change</button>
                    
                    <input dusk="paste">
                </div>
            HTML;
            }
        })
            ->assertSee('Your API')
            ->waitForLivewire()->click('@change')
            ->click('@tallstackui_clipboard_input_copy')
            ->keys('@paste', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'v'])
            ->assertInputValue('@paste', 'FooBarBazBah');
    }
}
