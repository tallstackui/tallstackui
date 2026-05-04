<?php

namespace TallStackUi\Components\Button\Group;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_click_each_grouped_button(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $picked = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button.group>
                        <x-button dusk="years"  text="Years"  wire:click="$set('picked', 'years')" />
                        <x-button dusk="months" text="Months" wire:click="$set('picked', 'months')" />
                        <x-button dusk="days"   text="Days"   wire:click="$set('picked', 'days')" />
                    </x-button.group>

                    <span dusk="picked">{{ $picked }}</span>
                </div>
                HTML;
            }
        })
            ->click('@months')
            ->waitForTextIn('@picked', 'months')
            ->click('@days')
            ->waitForTextIn('@picked', 'days');
    }

    #[Test]
    public function can_render_anchor_button_inside_group(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button.group>
                        <x-button text="Home" href="https://example.com/home" dusk="anchor" />
                        <x-button text="Next" />
                    </x-button.group>
                </div>
                HTML;
            }
        })
            ->assertVisible('@anchor')
            ->assertAttribute('@anchor', 'href', 'https://example.com/home');
    }
}
