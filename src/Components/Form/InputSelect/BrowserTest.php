<?php

namespace TallStackUi\Components\Form\InputSelect;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_clear_styled_select(): void
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
            ->clickAtVisibleXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForTextIn('@code', '+55')
            ->waitFor('@tallstackui_select_clear')
            ->click('@tallstackui_select_clear')
            ->waitUntilMissingText('+55')
            ->assertDontSeeIn('@code', '+55');
    }

    #[Test]
    public function can_hide_select_hint_inside_slot(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input.select label="Phone" wire:model="phone">
                        <x-slot:left>
                            <x-select.native :options="['BR', 'US']" hint="Pick a country" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertDontSee('Pick a country');
    }

    #[Test]
    public function can_hide_select_label_inside_slot(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input.select label="Phone" wire:model="phone">
                        <x-slot:left>
                            <x-select.styled :options="['+55', '+1']" label="Code" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('Phone')
            ->assertDontSee('Code');
    }

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
    public function can_search_in_styled_select(): void
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
                            <x-select.styled :options="['+55', '+1', '+44']" wire:model.live="code" searchable />
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
            ->type('@tallstackui_select_search_input', '+44')
            ->waitForText('+44')
            ->waitUntilMissingText('+55')
            ->assertDontSee('+55')
            ->assertSee('+44');
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
    public function can_see_validation_errors(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Validate('required')]
            public ?string $phone = null;

            public function save(): void
            {
                $this->validate();
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input.select label="Phone" dusk="phone_input" wire:model.live="phone">
                        <x-slot:left>
                            <x-select.native :options="['BR', 'US']" />
                        </x-slot:left>
                    </x-input.select>
                    <x-button dusk="save" wire:click="save">Save</x-button>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@save')
            ->waitForText('The phone field is required.')
            ->assertSee('The phone field is required.');
    }

    #[Test]
    public function can_select_native_on_right_and_bind_value(): void
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
            ->select('@country_select', 'UK')
            ->waitForTextIn('@country', 'UK');
    }

    #[Test]
    public function can_select_native_option_and_bind_value(): void
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
            ->select('@country_select', 'US')
            ->waitForTextIn('@country', 'US');
    }

    #[Test]
    public function can_select_styled_on_right_and_bind_value(): void
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
                        <x-slot:right>
                            <x-select.styled :options="['+55', '+1', '+44']" wire:model.live="code" />
                        </x-slot:right>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_select_open_close')
            ->waitFor('@tallstackui_select_options')
            ->waitForText('+1')
            ->clickAtVisibleXPath('//ul[@dusk="tallstackui_select_options"]/li[2]')
            ->waitForTextIn('@code', '+1');
    }

    #[Test]
    public function can_select_styled_option_and_bind_value(): void
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
            ->clickAtVisibleXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForTextIn('@code', '+55');
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
    public function can_use_both_native_and_input_simultaneously(): void
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
            ->select('@country_select', 'US')
            ->waitForTextIn('@country', 'US')
            ->type('@phone_input', '999888777')
            ->waitForTextIn('@phone', '999888777')
            ->assertSeeIn('@country', 'US');
    }

    #[Test]
    public function can_use_both_styled_and_input_simultaneously(): void
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
            ->click('@tallstackui_select_open_close')
            ->waitFor('@tallstackui_select_options')
            ->waitForText('+55')
            ->clickAtVisibleXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForTextIn('@code', '+55')
            ->type('@phone_input', '999888777')
            ->waitForTextIn('@phone', '999888777')
            ->assertSeeIn('@code', '+55');
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

    #[Test]
    public function can_use_disabled_input(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input.select label="Phone" dusk="phone_input" wire:model="phone" disabled>
                        <x-slot:left>
                            <x-select.native :options="['BR', 'US']" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertAttribute('@phone_input', 'disabled', 'true');
    }

    #[Test]
    public function can_use_icon(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input.select label="Phone" dusk="phone_input" wire:model="phone" icon="phone">
                        <x-slot:left>
                            <x-select.native :options="['BR', 'US']" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertPresent('@phone_input')
            ->assertSourceHas('svg');
    }

    #[Test]
    public function can_use_prefix(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $phone = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input.select label="Phone" dusk="phone_input" wire:model="phone" prefix="+55">
                        <x-slot:left>
                            <x-select.native :options="['BR', 'US']" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('+55');
    }

    #[Test]
    public function can_use_suffix(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input.select label="Amount" dusk="amount_input" wire:model="value" suffix="BRL">
                        <x-slot:left>
                            <x-select.native :options="['USD', 'EUR', 'BRL']" />
                        </x-slot:left>
                    </x-input.select>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('BRL');
    }
}
