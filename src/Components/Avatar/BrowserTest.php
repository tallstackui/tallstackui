<?php

namespace TallStackUi\Components\Avatar;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_bind_src(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div x-data="{image: 'https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png'}">
                    <x-avatar image x-bind:src="image" />
                </div>
                HTML;
            }
        })
            ->waitFor('img[src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"]')
            ->assertVisible('img[src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"]');
    }

    #[Test]
    public function can_render_presence_with_custom_color(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-avatar text="AJ" presence presence-color="red" />
                </div>
                HTML;
            }
        })
            ->assertSourceHas('bg-red-500');
    }

    #[Test]
    public function can_toggle_presence(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $online = false;

            public function toggle(): void
            {
                $this->online = ! $this->online;
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-avatar text="AJ" :presence="$online" />
                    <button wire:click="toggle" dusk="toggle">Toggle</button>
                </div>
                HTML;
            }
        })
            ->assertMissing('.bg-green-500')
            ->click('@toggle')
            ->waitFor('.bg-green-500')
            ->click('@toggle')
            ->waitUntilMissing('.bg-green-500');
    }

    #[Test]
    public function can_toggle_pulse(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $online = false;

            public function toggle(): void
            {
                $this->online = ! $this->online;
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-avatar text="AJ" :presence="$online" :pulse="$online" />
                    <button wire:click="toggle" dusk="toggle">Toggle</button>
                </div>
                HTML;
            }
        })
            ->assertMissing('.animate-ping')
            ->click('@toggle')
            ->waitFor('.animate-ping')
            ->click('@toggle')
            ->waitUntilMissing('.animate-ping');
    }
}
