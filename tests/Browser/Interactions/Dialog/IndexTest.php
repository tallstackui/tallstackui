<?php

namespace Tests\Browser\Interactions\Dialog;

use Livewire\Component;
use Livewire\Livewire;
use TallStackUi\Traits\Interactions;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_send(): void
    {
        Livewire::visit(DialogComponent::class)
            ->assertDontSee('Foo bar success')
            ->click('#success')
            ->waitForText('Foo bar success')
            ->click('@tallstackui_dialog_confirmation')
            ->assertDontSee('Foo bar error')
            ->click('#error')
            ->waitForText('Foo bar error')
            ->click('@tallstackui_dialog_confirmation')
            ->assertDontSee('Foo bar info')
            ->click('#info')
            ->waitForText('Foo bar info')
            ->click('@tallstackui_dialog_confirmation')
            ->assertDontSee('Foo bar warning')
            ->click('#warning')
            ->waitForText('Foo bar warning')
            ->click('@tallstackui_dialog_confirmation');
    }

    /** @test */
    public function can_send_cancellation(): void
    {
        Livewire::visit(DialogComponent::class)
            ->assertDontSee('Foo bar confirmation description')
            ->click('#confirm')
            ->waitForText('Foo bar confirmation description')
            ->click('@tallstackui_dialog_rejection')
            ->waitForText('Bar foo cancelled bar');
    }

    /** @test */
    public function can_send_confirmation(): void
    {
        Livewire::visit(DialogComponent::class)
            ->assertDontSee('Foo bar confirmation description')
            ->click('#confirm')
            ->waitForText('Foo bar confirmation description')
            ->click('@tallstackui_dialog_confirmation')
            ->waitUntilMissingText('Foo bar confirmation description');
    }

    /** @test */
    public function cannot_close_when_dialog_is_persistent(): void
    {
        config()->set('tallstackui.settings.dialog.persistent', true);

        Livewire::visit(DialogComponent::class)
            ->assertDontSee('Foo bar success')
            ->click('#success')
            ->waitForText('Foo bar success')
            ->assertSee('Foo bar success')
            ->clickAtPoint(350, 350)
            ->pause(100)
            ->waitForText('Foo bar success')
            ->assertSee('Foo bar success');
    }
}

class DialogComponent extends Component
{
    use Interactions;

    public function cancelled(string $message): void
    {
        $this->dialog()->success($message);
    }

    public function confirm(): void
    {
        $this->dialog()->confirm('Foo bar confirmation', 'Foo bar confirmation description', [
            'confirm' => [
                'text' => 'Confirm',
                'method' => 'confirmed',
                'params' => 'Foo bar confirmed foo',
            ],
            'cancel' => [
                'method' => 'cancelled',
                'params' => 'Bar foo cancelled bar',
            ],
        ]);
    }

    public function confirmed(string $message): void
    {
        $this->dialog()->success($message);
    }

    public function error(): void
    {
        $this->dialog()->error('Foo bar error');
    }

    public function info(): void
    {
        $this->dialog()->info('Foo bar info');
    }

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-button id="success" wire:click="success">Success</x-button>
            <x-button id="error" wire:click="error">Error</x-button>
            <x-button id="info" wire:click="info">Info</x-button>
            <x-button id="warning" wire:click="warning">Error</x-button>
            <x-button id="confirm" wire:click="confirm">Confirm</x-button>
        </div>
        HTML;
    }

    public function success(): void
    {
        $this->dialog()->success('Foo bar success');
    }

    public function warning(): void
    {
        $this->dialog()->warning('Foo bar warning');
    }
}
