<?php

namespace TallStackUi\Components\Form\Currency;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Laravel\Dusk\Browser;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_bind_and_clear(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable mutate />
                    
                    <x-button dusk="sync" wire:click="sync">Reset</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->reset('money');
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '1000')
            ->waitForTextIn('@money', '10.00')
            ->assertSeeIn('@money', '10.00')
            ->waitForLivewire()->click('@sync')
            ->assertInputValue('@input', '');
    }

    #[Test]
    public function can_bind_decimal_value_across_thousand_separator(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>

                    <x-currency dusk="input" wire:model.live="money" decimal />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '200000')
            ->assertInputValue('@input', '2,000.00')
            ->waitForTextIn('@money', '2000.00')
            ->assertSeeIn('@money', '2000.00');
    }

    #[Test]
    public function can_bind_decimal_value_with_pt_br_locale(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>

                    <x-currency dusk="input" locale="pt-BR" wire:model.live="money" decimal />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '150055')
            ->assertInputValue('@input', '1.500,55')
            ->waitForTextIn('@money', '1500.55')
            ->assertSeeIn('@money', '1500.55');
    }

    #[Test]
    public function can_bind_formatted(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>

                    <x-currency dusk="input" wire:model.live="money" clearable mutate />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '1000')
            ->waitForTextIn('@money', '10.00')
            ->assertSeeIn('@money', '10.00');
    }

    #[Test]
    public function can_bind_without_format(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '1000')
            ->waitForTextIn('@money', '1000')
            ->assertSeeIn('@money', '1000');
    }

    #[Test]
    public function can_clear(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" locale="pt-BR" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '1000')
            ->assertInputValue('@input', '10,00')
            ->click('@tallstackui_form_currency_clearable')
            ->pause(500)
            ->assertInputValue('@input', '')
            ->pause(100)
            ->assertNotVisible('@money');
    }

    #[Test]
    public function can_format_brl(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" locale="pt-BR" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '1000')
            ->assertInputValue('@input', '10,00');
    }

    #[Test]
    public function can_format_correctly(): void
    {
        Livewire::visit(new class extends Component
        {
            public float $money = 1041.3;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->pause(250)
            ->assertInputValue('@input', '1,041.30');
    }

    #[Test]
    public function can_format_with_three_decimals(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>

                    <x-currency dusk="input" wire:model.live="money" :decimals="3" :precision="5" mutate />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '12345')
            ->pause(500)
            ->assertInputValue('@input', '12.345');
    }

    #[Test]
    public function can_see_validation_error(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Validate('required')]
            public ?string $money = '10.00';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->pause(250)
            ->click('@tallstackui_form_currency_clearable')
            ->pause(250)
            ->assertSee('The money field is required.');
    }

    #[Test]
    public function cannot_insert_nan(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->pause(250)
            ->typeSlowly('@input', 'n')
            ->typeSlowly('@input', 'a')
            ->typeSlowly('@input', 'n')
            ->typeSlowly('@input', 'nan')
            ->assertNotVisible('@money');
    }

    #[Test]
    public function native_form_renders_the_initial_value(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-currency')
            ->waitFor('@filled')
            ->pause(250)
            ->assertInputValue('@filled', '9.990,00'));
    }

    #[Test]
    public function native_form_submits_a_single_named_input(): void
    {
        // Two inputs sharing the same name would leave PHP with the last one, which
        // is the visible, locale formatted one instead of the hidden raw value.
        $this->browse(fn (Browser $browser) => $browser->visit('/native-currency')
            ->waitFor('@input')
            ->assertScript("document.getElementsByName('price').length", 1));
    }

    #[Test]
    public function native_form_submits_the_decimal_value(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-currency')
            ->waitFor('@decimal')
            ->typeSlowly('@decimal', '123456')
            ->assertInputValue('@decimal', '1.234,56')
            ->click('@submit')
            ->waitForText('received:')
            ->assertSee('total:1234.56'));
    }

    #[Test]
    public function native_form_submits_the_raw_value(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-currency')
            ->waitFor('@input')
            ->typeSlowly('@input', '123456')
            ->assertInputValue('@input', '1.234,56')
            ->click('@submit')
            ->waitForText('received:')
            ->assertSee('received:123456'));
    }

    /**
     * A plain Blade page, with no Livewire component anywhere. Livewire's script is
     * still loaded because that is where Alpine comes from in a real application.
     *
     * @param  Router  $router
     */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-currency', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-currency/result">
                <x-currency dusk="input" name="price" symbol currency locale="pt-BR" />
                <x-currency dusk="decimal" name="total" decimal locale="pt-BR" />
                <x-currency dusk="filled" name="fee" value="9990" locale="pt-BR" />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML));

        $router->get('/native-currency/result', fn (Request $request): string => 'received:'.$request->query('price').' total:'.$request->query('total'));
    }
}
