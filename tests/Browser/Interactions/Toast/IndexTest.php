<?php

namespace Tests\Browser\Interactions\Toast;

use Livewire\Component;
use Livewire\Livewire;
use TallStackUi\Traits\Interactions;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_expand(): void
    {
        Livewire::visit(ToastComponent::class)
            ->assertDontSee('Lorem Ipsum is simply')
            ->click('#expand')
            ->waitForText('Lorem Ipsum is simply')
            ->click('@tallstackui_toast_expandable')
            ->waitForText('specimen')
            ->assertSee('specimen');
    }

    /** @test */
    public function can_expand_and_not_expand_sequentially(): void
    {
        Livewire::visit(ToastComponent::class)
            ->assertDontSee('Lorem Ipsum is simply')
            ->click('#expandConfirmation')
            ->waitForText('Lorem Ipsum is simply')
            ->click('@tallstackui_toast_expandable')
            ->waitForText('specimen')
            ->assertSee('specimen')
            ->click('@tallstackui_toast_confirmation')
            ->waitForText('chunks')
            ->assertSee('chunks');
    }

    /** @test */
    public function can_send(): void
    {
        Livewire::visit(ToastComponent::class)
            ->assertDontSee('Foo bar success')
            ->click('#success')
            ->waitForText('Foo bar success')
            ->assertDontSee('Foo bar error')
            ->click('#error')
            ->waitForText('Foo bar error')
            ->assertDontSee('Foo bar info')
            ->click('#info')
            ->waitForText('Foo bar info')
            ->assertDontSee('Foo bar warning')
            ->click('#warning')
            ->waitForText('Foo bar warning');
    }

    /** @test */
    public function can_send_cancellation(): void
    {
        Livewire::visit(ToastComponent::class)
            ->assertDontSee('Foo bar confirmation description')
            ->click('#confirm')
            ->waitForText('Foo bar confirmation description')
            ->click('@tallstackui_toast_rejection')
            ->waitForText('Bar foo cancelled bar');
    }

    /** @test */
    public function can_send_confirmation(): void
    {
        Livewire::visit(ToastComponent::class)
            ->assertDontSee('Foo bar confirmation description')
            ->click('#confirm')
            ->waitForText('Foo bar confirmation description')
            ->click('@tallstackui_toast_confirmation')
            ->waitForText('Foo bar confirmed foo');
    }
}

class ToastComponent extends Component
{
    use Interactions;

    public function cancelled(string $message): void
    {
        $this->toast()->success($message);
    }

    public function confirm(): void
    {
        $this->toast()->confirm('Foo bar confirmation', 'Foo bar confirmation description', [
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
        $this->toast()->success($message);
    }

    public function error(): void
    {
        $this->toast()->error('Foo bar error');
    }

    public function expand(): void
    {
        $this->toast()
            ->expandable()
            ->success('Foo bar', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.');
    }

    public function expandConfirmation(): void
    {
        $this->toast()
            ->expandable()
            ->confirm('Warning!', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', [
                'confirm' => [
                    'text' => 'Confirm',
                    'method' => 'notExpandable',
                    'params' => 'Confirmed Successfully',
                ],
            ]);
    }

    public function info(): void
    {
        $this->toast()->info('Foo bar info');
    }

    public function notExpandable(): void
    {
        $this->toast()
            ->expandable(false)
            ->success('Foo bar', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.');
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
            <x-button id="expand" wire:click="expand">Expand</x-button>
            <x-button id="expandConfirmation" wire:click="expandConfirmation">Expand Confirmation</x-button>
        </div>
        HTML;
    }

    public function success(): void
    {
        $this->toast()->success('Foo bar success');
    }

    public function warning(): void
    {
        $this->toast()->warning('Foo bar warning');
    }
}
