<?php

namespace Tests\Browser\Interactions\Toast\Components;

use Livewire\Attributes\On;
use Livewire\Component;
use TasteUi\Traits\Interactions;

class ToastCancellationComponent extends Component
{
    use Interactions;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-button id="confirm" wire:click="confirm">Confirm</x-button>
        </div>
HTML;
    }

    public function confirm(): void
    {
        $this->toast()->confirm('Foo bar confirmation', 'Foo bar confirmation description', [
            'confirm' => [
                'text' => 'Confirm',
                'event' => 'confirmed',
                'params' => 'Foo bar confirmed foo',
            ],
            'cancel' => [
                'text' => 'Cancel',
                'event' => 'cancelled',
                'params' => 'Bar foo cancelled bar',
            ],
        ]);
    }

    #[On('cancelled')]
    public function cancelled(string $message): void
    {
        $this->toast()->success($message);
    }
}
