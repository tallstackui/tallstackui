<?php

namespace Tests\Browser\Interactions\Toast;

use Livewire\Component;
use Livewire\Livewire;
use TallStackUi\Traits\Interactions;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_dispatch_confirmation_toast_without_livewire_specifing_component_id()
    {
        Livewire::visit(new class extends Component
        {
            use Interactions;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="confirm" onclick="confirm()">Confirm</x-button>
                    
                    <script>
                        confirm = () => $interaction('toast').question('Confirm?')
                            .wireable(Livewire.first().id)
                            .confirm('Confirm', 'confirmed', 'Confirmed Without Livewire')
                            .cancel('Cancel', 'cancelled', 'Cancelled Without Livewire')
                            .send();
                    </script>
                </div>
                HTML;
            }

            public function confirmed(string $message): void
            {
                $this->toast()->success($message)->send();
            }

            public function cancelled(string $message): void
            {
                $this->toast()->error($message)->send();
            }
        })
            ->assertDontSee('Confirm?')
            ->assertDontSee('Confirmed Without Livewire')
            ->click('@confirm')
            ->waitForText('Confirm?')
            ->click('@tallstackui_toast_confirmation')
            ->waitForText('Confirmed Without Livewire')
            ->assertSee('Confirmed Without Livewire')
            ->click('@confirm')
            ->waitForText('Confirm?')
            ->click('@tallstackui_toast_rejection')
            ->waitForText('Cancelled Without Livewire')
            ->assertSee('Cancelled Without Livewire');
    }

    /** @test */
    public function can_dispatch_confirmation_toast_without_livewire_using_first_component_in_page()
    {
        Livewire::visit(new class extends Component
        {
            use Interactions;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="confirm" onclick="confirm()">Confirm</x-button>
                    
                    <script>
                        confirm = () => $interaction('toast').question('Confirm?')
                            .wireable()
                            .confirm('Confirm', 'confirmed', 'Confirmed Without Livewire')
                            .cancel('Cancel', 'cancelled', 'Cancelled Without Livewire')
                            .send();
                    </script>
                </div>
                HTML;
            }

            public function confirmed(string $message): void
            {
                $this->toast()->success($message)->send();
            }
        })
            ->assertDontSee('Confirm?')
            ->assertDontSee('Confirmed Without Livewire')
            ->click('@confirm')
            ->waitForText('Confirm?')
            ->click('@tallstackui_toast_confirmation')
            ->waitForText('Confirmed Without Livewire')
            ->assertSee('Confirmed Without Livewire');
    }

    /** @test */
    public function can_dispatch_events()
    {
        Livewire::visit(new class extends Component
        {
            use Interactions;

            public string $target = '';

            public function cancelled(string $message): void
            {
                $this->toast()->success($message)->send();
            }

            public function confirm(): void
            {
                $this->toast()
                    ->question('Foo bar confirmation', 'Foo bar confirmation description')
                    ->confirm('Confirm', 'confirmed', 'Foo bar confirmed foo')
                    ->cancel('Cancel', 'cancelled', 'Bar foo cancelled bar')
                    ->send();
            }

            public function timeout(): void
            {
                $this->toast()
                    ->timeout(1)
                    ->question('Foo bar confirmation', 'Foo bar confirmation description')
                    ->confirm('Confirm', 'confirmed', 'Foo bar confirmed foo')
                    ->cancel('Cancel', 'cancelled', 'Bar foo cancelled bar')
                    ->send();
            }

            public function confirmed(string $message): void
            {
                $this->toast()->success($message)->send();
            }

            public function render(): string
            {
                return <<<'HTML'
                <div x-on:toast:accepted.window="$wire.set('target', 'Accepted')" 
                     x-on:toast:rejected.window="$wire.set('target', 'Rejected')"
                     x-on:toast:timeout.window="$wire.set('target', 'Timeout')">
                    <p dusk="target">{{ $target }}</p>
                
                    <x-button dusk="confirm" wire:click="confirm">Confirm</x-button>
                    <x-button dusk="timeout" wire:click="timeout">Timeout</x-button>
                </div>
                HTML;
            }
        })
            ->assertDontSee('Accepted')
            ->assertDontSee('Rejected')
            ->assertDontSeeIn('@target', 'Confirm')
            ->click('@confirm')
            ->waitForText('Foo bar confirmation')
            ->assertSee('Foo bar confirmation')
            ->click('@tallstackui_toast_confirmation')
            ->waitForTextIn('@target', 'Accepted')
            ->assertSeeIn('@target', 'Accepted')
            ->assertSee('Foo bar confirmed foo')
            ->click('@confirm')
            ->waitForText('Foo bar confirmation')
            ->assertSee('Foo bar confirmation')
            ->click('@tallstackui_toast_rejection')
            ->waitForTextIn('@target', 'Rejected')
            ->assertSeeIn('@target', 'Rejected')
            ->waitForText('Bar foo cancelled bar')
            ->assertSee('Bar foo cancelled bar')
            ->click('@timeout')
            ->waitForTextIn('@target', 'Timeout')
            ->assertSeeIn('@target', 'Timeout');
    }

    /** @test */
    public function can_dispatch_toast_without_livewire()
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="success" x-on:click="$interaction('toast').success('Success Without Livewire').send()">Success</x-button>
                    <x-button dusk="error" x-on:click="$interaction('toast').error('Error Without Livewire').send()">Error</x-button>
                    <x-button dusk="info" x-on:click="$interaction('toast').info('Info Without Livewire').send()">Info</x-button>
                    <x-button dusk="warning" x-on:click="$interaction('toast').warning('Warning Without Livewire').send()">Error</x-button>
                </div>
                HTML;
            }
        })
            ->assertDontSee('Success Without Livewire')
            ->click('@success')
            ->waitForText('Success Without Livewire')
            ->assertDontSee('Error Without Livewire')
            ->click('@error')
            ->waitForText('Error Without Livewire')
            ->assertDontSee('Info Without Livewire')
            ->click('@info')
            ->waitForText('Info Without Livewire')
            ->assertDontSee('Warning Without Livewire')
            ->click('@warning')
            ->waitForText('Warning Without Livewire');
    }

    /** @test */
    public function can_expand(): void
    {
        Livewire::visit(ToastComponent::class)
            ->assertDontSee('Lorem Ipsum is simply')
            ->click('#expand')
            ->waitForText('Lorem Ipsum is simply')
            ->mouseover('@tallstackui_toast_expandable')
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
            ->mouseover('@tallstackui_toast_expandable')
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

    /** @test */
    public function cannot_see_cancellation_if_it_was_not_defined(): void
    {
        Livewire::visit(new class extends Component
        {
            use Interactions;

            public function confirm(): void
            {
                $this->toast()
                    ->question('Foo bar confirmation', 'Foo bar confirmation description')
                    ->confirm('Confirm', 'confirmed', 'Foo bar confirmed foo')
                    ->send();
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="confirm" wire:click="confirm">Confirm</x-button>
                </div>
                HTML;
            }
        })
            ->assertDontSee('Foo bar confirmation')
            ->click('@confirm')
            ->waitForText('Foo bar confirmation')
            ->assertSee('Foo bar confirmation')
            ->assertSee('Foo bar confirmation description')
            ->assertDontSee('Cancelled');
    }
}

class ToastComponent extends Component
{
    use Interactions;

    public function cancelled(string $message): void
    {
        $this->toast()->success($message)->send();
    }

    public function confirm(): void
    {
        $this->toast()
            ->question('Foo bar confirmation', 'Foo bar confirmation description')
            ->confirm('Confirm', 'confirmed', 'Foo bar confirmed foo')
            ->cancel('Cancel', 'cancelled', 'Bar foo cancelled bar')
            ->send();
    }

    public function confirmed(string $message): void
    {
        $this->toast()->success($message)->send();
    }

    public function error(): void
    {
        $this->toast()->error('Foo bar error')->send();
    }

    public function expand(): void
    {
        $this->toast()
            ->expandable()
            ->success('Foo bar', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.')
            ->send();
    }

    public function expandConfirmation(): void
    {
        $this->toast()
            ->expandable()
            ->question('Foo bar confirmation', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.')
            ->confirm('Confirm', 'notExpandable')
            ->send();
    }

    public function info(): void
    {
        $this->toast()->info('Foo bar info')->send();
    }

    public function notExpandable(): void
    {
        $this->toast()
            ->expandable(false)
            ->success('Foo bar', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.')
            ->send();
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
        $this->toast()->success('Foo bar success')->send();
    }

    public function warning(): void
    {
        $this->toast()->warning('Foo bar warning')->send();
    }
}
