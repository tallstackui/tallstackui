<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class ColorTest extends BrowserTestCase
{
    /** @test */
    public function can_open_and_select_color_with_live_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $color;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="selected">{{ $color }}</p>
                    
                    <x-color label="Color" wire:model.live="color" />
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForText('Color')
            ->click('@tallstackui_form_color')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[4]/div/div/button[1]')
            ->click('@sync')
            ->waitForTextIn('@selected', '#64748b');
    }

    /** @test */
    public function can_open_and_select_first_color(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $color;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="selected">{{ $color }}</p>
                    
                    <x-color label="Color" wire:model="color" />
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForText('Color')
            ->click('@tallstackui_form_color')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[4]/div/div/button[1]')
            ->click('@sync')
            ->waitForTextIn('@selected', '#64748b');
    }

    /** @test */
    public function can_open_and_select_first_color_in_mode_custom(): void
    {
        if (getenv('GITHUB_ACTIONS') === 'true') {
            $this->markTestSkipped('For some unknown reason this test fails on GitHub Actions.');
        }

        Livewire::visit(new class extends Component
        {
            public ?string $color;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="selected">{{ $color }}</p>
                    
                    <x-color label="Color" wire:model="color" :colors="['#FF0000', '#FF5733', '#D7E021']" />
                    
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForText('Color')
            ->click('@tallstackui_form_color')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[4]/div/div/button[1]')
            ->click('@sync')
            ->waitForTextIn('@selected', '#FF0000')
            ->assertSee('#FF0000')
            ->click('@tallstackui_form_color')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[4]/div/div/button[2]')
            ->click('@sync')
            ->waitForTextIn('@selected', '#FF5733')
            ->assertSee('#FF5733')
            ->click('@tallstackui_form_color')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[4]/div/div/button[3]')
            ->click('@sync')
            ->waitForTextIn('@selected', '#D7E021')
            ->assertSee('#D7E021');
    }

    /** @test */
    public function can_open_and_select_first_color_in_mode_picker(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $color;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="selected">{{ $color }}</p>
                    
                    <x-color label="Color" wire:model="color" picker />
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForText('Color')
            ->click('@tallstackui_form_color')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[4]/div/div/button[1]')
            ->click('@sync')
            ->waitForTextIn('@selected', '#f8fafc');
    }

    /** @test */
    public function can_open_and_select_first_color_in_mode_range(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $color = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="selected">{{ $color }}</p>
                    
                    <x-color label="Color" wire:model="color" />
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForText('Color')
            ->click('@tallstackui_form_color')
            ->dragRight('@tallstackui_form_range', 50)
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[4]/div/div/button[8]')
            ->click('@sync')
            ->waitForTextIn('@selected', '#b45309');
    }

    /** @test */
    public function can_open_select_a_color_and_dispatch_change_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $color;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="selected">{{ $color }}</p>
                    
                    <x-color wire:change="sync" label="Color" wire:model="color" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForText('Color')
            ->click('@tallstackui_form_color')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[4]/div/div/button[1]')
            ->waitForTextIn('@selected', '#64748b');
    }
}
