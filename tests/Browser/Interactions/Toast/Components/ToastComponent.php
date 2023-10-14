<?php

namespace Tests\Browser\Interactions\Toast\Components;

use Livewire\Component;
use TallStackUi\Traits\Interactions;

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

    public function info(): void
    {
        $this->toast()->info('Foo bar info');
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
        $this->toast()->success('Foo bar success');
    }

    public function warning(): void
    {
        $this->toast()->warning('Foo bar warning');
    }
}
