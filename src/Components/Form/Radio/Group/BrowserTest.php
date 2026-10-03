<?php

namespace TallStackUi\Components\Form\Radio\Group;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_select_an_option(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $plan = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="output">{{ $plan }}</p>

                    <x-radio.group wire:model.live="plan" :options="[
                        ['label' => 'Startup', 'value' => 'startup'],
                        ['label' => 'Business', 'value' => 'business'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertDontSeeIn('@output', 'business')
            ->click('label:has(#plan-1)')
            ->waitForTextIn('@output', 'business')
            ->assertSeeIn('@output', 'business');
    }

    #[Test]
    public function can_select_an_option_when_another_component_binds_the_same_property(): void
    {
        Livewire::visit([
            new class extends Component
            {
                public function render(): string
                {
                    return <<<'HTML'
                    <div>
                        <livewire:child name="first" />

                        <livewire:child name="second" />
                    </div>
                    HTML;
                }
            },
            'child' => new class extends Component
            {
                public string $name;

                public ?string $plan = null;

                public function render(): string
                {
                    return <<<'HTML'
                    <div dusk="{{ $name }}">
                        <p dusk="{{ $name }}-output">{{ $plan ?? 'empty' }}</p>

                        <x-radio.group wire:model.live="plan" :options="[
                            ['label' => 'Startup', 'value' => 'startup'],
                            ['label' => 'Business', 'value' => 'business'],
                        ]" />
                    </div>
                    HTML;
                }
            },
        ])
            ->click('@second input[value="business"] + span')
            ->waitForTextIn('@second-output', 'business')
            ->assertSeeIn('@first-output', 'empty');
    }

    #[Test]
    public function can_select_an_option_when_the_control_is_hidden(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $plan = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="output">{{ $plan }}</p>

                    <x-radio.group wire:model.live="plan" panel :options="[
                        ['label' => 'Startup', 'value' => 'startup'],
                        ['label' => 'Business', 'value' => 'business'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('label:has(#plan-1)')
            ->waitForTextIn('@output', 'business')
            ->assertSeeIn('@output', 'business');
    }

    #[Test]
    public function cannot_select_a_disabled_option(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $plan = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="output">{{ $plan ?? 'empty' }}</p>

                    <x-radio.group wire:model.live="plan" :options="[
                        ['label' => 'Startup', 'value' => 'startup'],
                        ['label' => 'Free', 'value' => 'free', 'disabled' => true],
                    ]" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('label:has(#plan-1)')
            ->pause(500)
            ->assertSeeIn('@output', 'empty');
    }
}
