<?php

namespace Tests\Browser\Interactions\Dialog\Components;

use Livewire\Component;
use TallStackUi\Traits\Interactions;

class DialogComponent extends Component
{
    use Interactions;

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

    public function error(): void
    {
        $this->dialog()->error('Foo bar error');
    }

    public function info(): void
    {
        $this->dialog()->info('Foo bar info');
    }

    public function warning(): void
    {
        $this->dialog()->warning('Foo bar warning');
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

    public function cancelled(string $message): void
    {
        $this->dialog()->success($message);
    }
}
