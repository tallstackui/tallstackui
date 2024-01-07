<?php

namespace Tests\Browser\Interactions\Banner;

use Livewire\Component;
use Livewire\Livewire;
use TallStackUi\Traits\Interactions;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_dispatch_and_close(): void
    {
        Livewire::visit(new class extends Component
        {
            use Interactions;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="success" wire:click="success">Success</x-button>
                </div>
                HTML;
            }

            public function success(): void
            {
                $this->banner()
                    ->close()
                    ->success('Foo bar success')
                    ->send();
            }
        })
            ->assertDontSee('Foo bar success')
            ->click('@success')
            ->waitForText('Foo bar success')
            ->assertSee('Foo bar success')
            ->click('@tallstackui_banner_close')
            ->waitUntilMissingText('Foo bar success')
            ->assertDontSee('Foo bar success');
    }

    /** @test */
    public function can_dispatch_error(): void
    {
        Livewire::visit(BannerComponent::class)
            ->assertDontSee('Foo bar error')
            ->click('@error')
            ->waitForText('Foo bar error')
            ->assertSee('Foo bar error');
    }

    /** @test */
    public function can_dispatch_info(): void
    {
        Livewire::visit(BannerComponent::class)
            ->assertDontSee('Foo bar info')
            ->click('@info')
            ->waitForText('Foo bar info')
            ->assertSee('Foo bar info');
    }

    /** @test */
    public function can_dispatch_success(): void
    {
        Livewire::visit(BannerComponent::class)
            ->assertDontSee('Foo bar success')
            ->click('@success')
            ->waitForText('Foo bar success')
            ->assertSee('Foo bar success');
    }

    /** @test */
    public function can_dispatch_warning(): void
    {
        Livewire::visit(BannerComponent::class)
            ->assertDontSee('Foo bar warning')
            ->click('@warning')
            ->waitForText('Foo bar warning')
            ->assertSee('Foo bar warning');
    }

    /** @test */
    public function can_dispatch_with_enter_delay(): void
    {
        Livewire::visit(BannerEnterLeaveComponent::class)
            ->assertDontSee('Foo bar enter')
            ->click('@enter')
            ->waitForText('Foo bar enter')
            ->assertSee('Foo bar enter');
    }

    /** @test */
    public function can_dispatch_with_leave_delay(): void
    {
        Livewire::visit(BannerEnterLeaveComponent::class)
            ->assertDontSee('Foo bar leave')
            ->click('@leave')
            ->waitForText('Foo bar leave')
            ->assertSee('Foo bar leave')
            ->waitUntilMissingText('Foo bar leave')
            ->assertDontSee('Foo bar leave');
    }
}

class BannerComponent extends Component
{
    use Interactions;

    public function error(): void
    {
        $this->banner()->error('Foo bar error')->send();
    }

    public function info(): void
    {
        $this->banner()->info('Foo bar info')->send();
    }

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-button dusk="success" wire:click="success">Success</x-button>
            <x-button dusk="error" wire:click="error">Error</x-button>
            <x-button dusk="info" wire:click="info">Info</x-button>
            <x-button dusk="warning" wire:click="warning">Error</x-button>
        </div>
        HTML;
    }

    public function success(): void
    {
        $this->banner()->success('Foo bar success')->send();
    }

    public function warning(): void
    {
        $this->banner()->warning('Foo bar warning')->send();
    }
}

class BannerEnterLeaveComponent extends Component
{
    use Interactions;

    public function enter(): void
    {
        $this->banner()
            ->enter(2)
            ->success('Foo bar enter')
            ->send();
    }

    public function leave(): void
    {
        $this->banner()
            ->leave(2)
            ->success('Foo bar leave')
            ->send();
    }

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-button dusk="enter" wire:click="enter">Enter</x-button>
            <x-button dusk="leave" wire:click="leave">Leave</x-button>
        </div>
        HTML;
    }
}
