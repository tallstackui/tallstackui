<?php

namespace TallStackUi\Components\Form\Checkbox\Group;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_select_many_options(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $features = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="output">{{ implode(',', $features) }}</p>

                    <x-checkbox.group wire:model.live="features" :options="[
                        ['label' => 'Newsletter', 'value' => 'newsletter'],
                        ['label' => 'Alerts', 'value' => 'alerts'],
                        ['label' => 'Reports', 'value' => 'reports'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('label[for="features-0"]')
            ->waitForTextIn('@output', 'newsletter')
            ->click('label[for="features-2"]')
            ->waitForTextIn('@output', 'reports')
            ->assertSeeIn('@output', 'newsletter,reports');
    }

    #[Test]
    public function can_unselect_an_option(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $features = ['newsletter'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="output">{{ $features === [] ? 'empty' : implode(',', $features) }}</p>

                    <x-checkbox.group wire:model.live="features" :options="[
                        ['label' => 'Newsletter', 'value' => 'newsletter'],
                        ['label' => 'Alerts', 'value' => 'alerts'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@output', 'newsletter')
            ->click('label[for="features-0"]')
            ->waitForTextIn('@output', 'empty')
            ->assertSeeIn('@output', 'empty');
    }

    #[Test]
    public function cannot_select_a_disabled_option(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $features = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="output">{{ $features === [] ? 'empty' : implode(',', $features) }}</p>

                    <x-checkbox.group wire:model.live="features" :options="[
                        ['label' => 'Newsletter', 'value' => 'newsletter'],
                        ['label' => 'Legacy', 'value' => 'legacy', 'disabled' => true],
                    ]" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('label[for="features-1"]')
            ->pause(500)
            ->assertSeeIn('@output', 'empty');
    }
}
