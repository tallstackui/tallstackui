<?php

namespace TallStackUi\Components\Form\InputSelect;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_interact_with_styled_select(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $code = null;

            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="code">{{ $code }}</p>

                    <x-input.select label="Phone" dusk="phone_input" wire:model.live="phone">
                        <x-slot:left>
                            <x-select.styled :options="['+55', '+1', '+44']" wire:model.live="code" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_select_open_close')
            ->waitFor('@tallstackui_select_options')
            ->waitForText('+55')
            ->assertSee('+55')
            ->assertSee('+1')
            ->assertSee('+44');
    }

    #[Test]
    public function can_render_with_native_select_on_left(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $country = null;

            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="country">{{ $country }}</p>
                    <p dusk="phone">{{ $phone }}</p>

                    <x-input.select label="Phone" dusk="phone_input" wire:model.live="phone">
                        <x-slot:left>
                            <x-select.native dusk="country_select" :options="['BR', 'US', 'UK']" wire:model.live="country" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertPresent('@country_select')
            ->assertPresent('@phone_input')
            ->assertSee('Phone');
    }

    #[Test]
    public function can_render_with_native_select_on_right(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $country = null;

            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="country">{{ $country }}</p>
                    <p dusk="phone">{{ $phone }}</p>

                    <x-input.select label="Phone" dusk="phone_input" wire:model.live="phone">
                        <x-slot:right>
                            <x-select.native dusk="country_select" :options="['BR', 'US', 'UK']" wire:model.live="country" />
                        </x-slot:right>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertPresent('@country_select')
            ->assertPresent('@phone_input')
            ->assertSee('Phone');
    }

    #[Test]
    public function can_render_with_styled_select_on_left(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $code = null;

            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="code">{{ $code }}</p>
                    <p dusk="phone">{{ $phone }}</p>

                    <x-input.select label="Phone" dusk="phone_input" wire:model.live="phone">
                        <x-slot:left>
                            <x-select.styled :options="['+55', '+1', '+44']" wire:model.live="code" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertPresent('@phone_input')
            ->assertPresent('@tallstackui_select_open_close');
    }

    #[Test]
    public function can_render_with_styled_select_on_right(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $code = null;

            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="code">{{ $code }}</p>
                    <p dusk="phone">{{ $phone }}</p>

                    <x-input.select label="Phone" dusk="phone_input" wire:model.live="phone">
                        <x-slot:right>
                            <x-select.styled :options="['+55', '+1', '+44']" wire:model.live="code" />
                        </x-slot:right>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertPresent('@phone_input')
            ->assertPresent('@tallstackui_select_open_close');
    }

    #[Test]
    public function can_see_label_and_hint(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input.select label="Phone Number" hint="Enter your phone" wire:model="phone">
                        <x-slot:left>
                            <x-select.native :options="['BR', 'US']" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('Phone Number')
            ->assertSee('Enter your phone');
    }

    #[Test]
    public function can_type_in_input(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="phone">{{ $phone }}</p>

                    <x-input.select label="Phone" dusk="phone_input" wire:model.live="phone">
                        <x-slot:left>
                            <x-select.native :options="['BR', 'US']" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->type('@phone_input', '999888777')
            ->waitForTextIn('@phone', '999888777');
    }

    #[Test]
    public function can_use_clearable(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $phone = 'initial value';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="phone">{{ $phone }}</p>

                    <x-input.select label="Phone" dusk="phone_input" wire:model.live="phone" clearable>
                        <x-slot:left>
                            <x-select.native :options="['BR', 'US']" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertPresent('@tallstackui_form_input_clearable')
            ->click('@tallstackui_form_input_clearable')
            ->waitForLivewire()
            ->waitUntilMissingText('initial value')
            ->assertDontSeeIn('@phone', 'initial value');
    }
}
