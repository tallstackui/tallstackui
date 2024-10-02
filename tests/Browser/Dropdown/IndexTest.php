<?php

namespace Tests\Browser\Dropdown;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_render_with_action(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown>
                        <x-slot:action>
                            <x-button id="action" x-on:click="show = !show">
                                FooBar
                            </x-button>
                        </x-slot:action>
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.items text="Ipsum" />
                    </x-dropdown>
                </div>
            HTML;
            }
        })
            ->assertSee('FooBar')
            ->click('#action')
            ->waitForText('Lorem')
            ->waitForText('Ipsum')
            ->click('#action')
            ->waitUntilMissingText('Lorem')
            ->waitUntilMissingText('Ipsum');
    }

    /** @test */
    public function can_render_with_icon(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-dropdown icon="chevron-down">
                            <x-dropdown.items text="Lorem" />
                            <x-dropdown.items text="Ipsum" />
                        </x-dropdown>
                    </div>
                HTML;
            }
        })
            ->click('@open-dropdown')
            ->waitForText('Lorem')
            ->waitForText('Ipsum')
            ->assertSee('Lorem')
            ->assertSee('Ipsum')
            ->click('@open-dropdown')
            ->waitUntilMissingText('Lorem')
            ->waitUntilMissingText('Ipsum');
    }

    /** @test */
    public function can_render_with_submenu(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown text="FooBar">
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.items text="Ipsum" />
                        <x-dropdown.submenu text="Submenu">
                            <x-dropdown.items text="Item 1" />
                            <x-dropdown.items text="Item 2" />
                        </x-dropdown.submenu>
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->assertSee('FooBar')
            ->click('@open-dropdown')
            ->waitForText('Lorem')
            ->waitForText('Ipsum')
            ->clickAtXPath('/html/body/div[3]/div/div/div[2]/div/div/button')
            ->waitForText('Item 1')
            ->assertSee('Item 1')
            ->click('@open-dropdown')
            ->waitUntilMissingText('Item 1')
            ->waitUntilMissingText('Lorem')
            ->waitUntilMissingText('Ipsum');
    }

    /** @test */
    public function can_render_with_title(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown text="FooBar">
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.items text="Ipsum" />
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->assertSee('FooBar')
            ->click('@open-dropdown')
            ->waitForText('Lorem')
            ->waitForText('Ipsum')
            ->click('@open-dropdown')
            ->waitUntilMissingText('Lorem')
            ->waitUntilMissingText('Ipsum');
    }
}
