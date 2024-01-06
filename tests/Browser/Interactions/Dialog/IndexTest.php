<?php

namespace Tests\Browser\Interactions\Dialog;

use Livewire\Component;
use Livewire\Livewire;
use TallStackUi\Traits\Interactions;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_dispatch_confirmation_dialog_without_livewire_specifing_component_id()
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
                        confirm = () => $dialog('Confirm?').confirm({
                            'confirm': {
                                'text': 'Confirm',
                                'method': 'confirmed',
                                'params': 'Confirmed Without Livewire'
                            },
                            'cancel': {
                                'text': 'Cancel',
                                'method': 'cancelled',
                                'params': 'Cancelled Without Livewire'
                            }
                        }, Livewire.first().id);
                    </script>
                </div>
                HTML;
            }

            public function confirmed(string $message): void
            {
                $this->dialog()->success($message);
            }

            public function cancelled(string $message): void
            {
                $this->dialog()->error($message);
            }
        })
            ->assertDontSee('Confirm?')
            ->assertDontSee('Confirmed Without Livewire')
            ->click('@confirm')
            ->waitForText('Confirm?')
            ->click('@tallstackui_dialog_confirmation')
            ->waitForText('Confirmed Without Livewire')
            ->assertSee('Confirmed Without Livewire')
            ->click('@tallstackui_dialog_confirmation')
            ->click('@confirm')
            ->waitForText('Confirm?')
            ->click('@tallstackui_dialog_rejection')
            ->waitForText('Cancelled Without Livewire')
            ->assertSee('Cancelled Without Livewire');
    }

    /** @test */
    public function can_dispatch_confirmation_dialog_without_livewire_using_first_component_in_page()
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
                        confirm = () => $dialog('Confirm?').confirm({
                            'confirm': {
                                'text': 'Confirm',
                                'method': 'confirmed',
                                'params': 'Confirmed Without Livewire'
                            }
                        }, '');
                    </script>
                </div>
                HTML;
            }

            public function confirmed(string $message): void
            {
                $this->dialog()->success($message);
            }
        })
            ->assertDontSee('Confirm?')
            ->assertDontSee('Confirmed Without Livewire')
            ->click('@confirm')
            ->waitForText('Confirm?')
            ->click('@tallstackui_dialog_confirmation')
            ->waitForText('Confirmed Without Livewire')
            ->assertSee('Confirmed Without Livewire');
    }

    /** @test */
    public function can_dispatch_dialog_without_livewire()
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="success" x-on:click="$dialog('Success Without Livewire').success()">Success</x-button>
                    <x-button dusk="error" x-on:click="$dialog('Error Without Livewire').error()">Error</x-button>
                    <x-button dusk="info" x-on:click="$dialog('Info Without Livewire').info()">Info</x-button>
                    <x-button dusk="warning" x-on:click="$dialog('Warning Without Livewire').warning()">Error</x-button>
                </div>
                HTML;
            }
        })
            ->assertDontSee('Success Without Livewire')
            ->click('@success')
            ->waitForText('Success Without Livewire')
            ->click('@tallstackui_dialog_confirmation')
            ->assertDontSee('Error Without Livewire')
            ->click('@error')
            ->waitForText('Error Without Livewire')
            ->click('@tallstackui_dialog_confirmation')
            ->assertDontSee('Info Without Livewire')
            ->click('@info')
            ->waitForText('Info Without Livewire')
            ->click('@tallstackui_dialog_confirmation')
            ->assertDontSee('Warning Without Livewire')
            ->click('@warning')
            ->waitForText('Warning Without Livewire')
            ->click('@tallstackui_dialog_confirmation');
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

            public function render(): string
            {
                return <<<'HTML'
                <div x-on:dialog:accepted.window="$wire.$set('target', 'Accepted')" 
                     x-on:dialog:rejected.window="$wire.$set('target', 'Rejected')">
                    <p dusk="target">{{ $target }}</p>
                
                    <x-button dusk="confirm" wire:click="confirm">Confirm</x-button>
                </div>
                HTML;
            }
        })
            ->assertDontSee('Accepted')
            ->assertDontSee('Rejected')
            ->assertSee('Confirm')
            ->click('@confirm')
            ->waitForText('Foo bar confirmation')
            ->assertSee('Foo bar confirmation')
            ->click('@tallstackui_dialog_confirmation')
            ->waitForText('Accepted')
            ->assertSee('Accepted')
            ->assertSee('Foo bar confirmed foo')
            ->click('@tallstackui_dialog_confirmation')
            ->click('@confirm')
            ->waitForText('Foo bar confirmation')
            ->assertSee('Foo bar confirmation')
            ->click('@tallstackui_dialog_rejection')
            ->waitForText('Rejected')
            ->assertSee('Rejected')
            ->waitForText('Bar foo cancelled bar')
            ->assertSee('Bar foo cancelled bar');
    }

    /** @test */
    public function can_dispatch_dismissed_event()
    {
        Livewire::visit(new class extends Component
        {
            use Interactions;

            public string $target = '';

            public function success(): void
            {
                $this->dialog()->success('Foo bar success', 'Foo bar success description');
            }

            public function render(): string
            {
                return <<<'HTML'
                <div x-on:dialog:dismissed.window="$wire.$set('target', 'Dismissed')">
                    <p dusk="target">{{ $target }}</p>
                
                    <x-button dusk="success" wire:click="success">Success</x-button>
                </div>
                HTML;
            }
        })
            ->assertDontSee('Dismissed')
            ->assertSee('Success')
            ->click('@success')
            ->waitForText(['Foo bar success', 'Foo bar success description'])
            ->assertSee('Foo bar success',)
            ->assertSee('Foo bar success description')
            ->clickAtPoint(350, 350)
            ->click('@tallstackui_dialog_confirmation')
            ->waitForTextIn('@target', 'Dismissed')
            ->assertSee('Dismissed');
    }

    /** @test */
    public function can_send(): void
    {
        Livewire::visit(DialogComponent::class)
            ->assertDontSee('Foo bar success')
            ->click('@success')
            ->waitForText('Foo bar success')
            ->click('@tallstackui_dialog_confirmation')
            ->assertDontSee('Foo bar error')
            ->click('@error')
            ->waitForText('Foo bar error')
            ->click('@tallstackui_dialog_confirmation')
            ->assertDontSee('Foo bar info')
            ->click('@info')
            ->waitForText('Foo bar info')
            ->click('@tallstackui_dialog_confirmation')
            ->assertDontSee('Foo bar warning')
            ->click('@warning')
            ->waitForText('Foo bar warning')
            ->click('@tallstackui_dialog_confirmation');
    }

    /** @test */
    public function can_send_cancellation(): void
    {
        Livewire::visit(DialogComponent::class)
            ->assertDontSee('Foo bar confirmation description')
            ->click('@confirm')
            ->waitForText('Foo bar confirmation description')
            ->click('@tallstackui_dialog_rejection')
            ->waitForText('Bar foo cancelled bar');
    }

    /** @test */
    public function can_send_confirmation(): void
    {
        Livewire::visit(DialogComponent::class)
            ->assertDontSee('Foo bar confirmation description')
            ->click('@confirm')
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
            ->click('@success')
            ->waitForText('Foo bar success')
            ->assertSee('Foo bar success')
            ->clickAtPoint(350, 350)
            ->pause(100)
            ->waitForText('Foo bar success')
            ->assertSee('Foo bar success');
    }

    /** @test */
    public function cannot_see_cancellation_if_it_was_not_defined(): void
    {
        Livewire::visit(new class extends Component
        {
            use Interactions;

            public function confirm(): void
            {
                $this->dialog()->confirm('Foo bar confirmation', 'Foo bar confirmation description', [
                    'confirm' => [
                        'text' => 'Confirm',
                        'method' => 'confirmed',
                        'params' => 'Foo bar confirmed foo',
                    ],
                ]);
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
            <x-button dusk="success" wire:click="success">Success</x-button>
            <x-button dusk="error" wire:click="error">Error</x-button>
            <x-button dusk="info" wire:click="info">Info</x-button>
            <x-button dusk="warning" wire:click="warning">Error</x-button>
            <x-button dusk="confirm" wire:click="confirm">Confirm</x-button>
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
