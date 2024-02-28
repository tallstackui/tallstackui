<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class DatePickerTest extends BrowserTestCase
{
    /** @test */
    public function can_advance_to_next_month(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_datepicker_open_close')
            ->waitForText('January')
            ->click('@tallstackui_datepicker_previous_month')
            ->waitForText('December')
            ->assertSee('December');
    }

    /** @test */
    public function can_advance_to_next_year(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_datepicker_open_close')
            ->waitForText('2020')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/span/button[2]')
            ->waitForText('2009')
            ->assertSee('2009')
            ->click('@tallstackui_datepicker_previous_year')
            ->waitForText('1990')
            ->assertSee('1990');
    }

    /** @test */
    public function can_interact_with_clear_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public ?bool $selected = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    @if ($selected)
                        <p dusk="selected">{{ $selected }}</p>
                    @endif
                    
                    <x-datepicker label="DatePicker"
                                  x-on:select="$wire.set('selected', 1)"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '2020-01-01')
            ->assertSeeIn('@date', '2020-01-01')
            ->click('@tallstackui_datepicker_open_close')
            ->clickAtXPath('/html/body/div[3]/div/div[1]/div/div/span/div/button[1]')
            ->waitForTextIn('@selected', '1')
            ->assertSeeIn('@selected', '1')
            ->assertVisible('@selected')
            ->assertDontSeeIn('@date', '2020-01-01');
    }

    /** @test */
    public function can_interact_with_select_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public ?bool $clear = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    @if ($clear)
                        <p dusk="clear">{{ $clear }}</p>
                    @endif
                    
                    <x-datepicker label="DatePicker"
                                  x-on:select="$wire.set('clear', 1)"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '2020-01-01')
            ->assertSeeIn('@date', '2020-01-01')
            ->click('@tallstackui_datepicker_clear')
            ->waitForTextIn('@clear', '1')
            ->assertSeeIn('@clear', '1');
    }

    /** @test */
    public function can_open_month_selector_and_navigate(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_datepicker_open_close')
            ->waitForText('January')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/span/button[1]')
            ->waitForText(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])
            ->assertSee('Jan')
            ->assertSee('Mar')
            ->assertSee('Dec');
    }

    /** @test */
    public function can_previous_to_last_year(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_datepicker_open_close')
            ->waitForText('2020')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/span/button[2]')
            ->waitForText('2009')
            ->assertSee('2009')
            ->click('@tallstackui_datepicker_next_year')
            ->waitForText('2040')
            ->assertSee('2040');
    }

    /** @test */
    public function can_previous_to_previous_month(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_datepicker_open_close')
            ->waitForText('January')
            ->click('@tallstackui_datepicker_next_month')
            ->waitForText('February')
            ->assertSee('February');
    }

    /** @test */
    public function can_select_date()
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '2020-01-01')
            ->assertSeeIn('@date', '2020-01-01')
            ->click('@tallstackui_datepicker_open_close')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[5]/button')
            ->waitForTextIn('@date', '2020-01-02')
            ->assertSeeIn('@date', '2020-01-02');
    }

    /** @test */
    public function can_select_date_on_multiple()
    {
        Livewire::visit(new class extends Component
        {
            public ?array $date = ['2020-01-01', '2020-01-03'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">@json($date)</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" 
                                  multiple />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '["2020-01-01","2020-01-03"]')
            ->assertSeeIn('@date', '["2020-01-01","2020-01-03"]')
            ->click('@tallstackui_datepicker_open_close')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[4]/button')
            ->waitForTextIn('@date', '["2020-01-03"]')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[5]/button')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[7]/button')
            ->waitForTextIn('@date', '["2020-01-03","2020-01-02","2020-01-04"]')
            ->assertSeeIn('@date', '["2020-01-03","2020-01-02","2020-01-04"]');
    }

    /** @test */
    public function can_select_date_on_range()
    {
        Livewire::visit(new class extends Component
        {
            public ?array $date = ['2020-01-01', '2020-01-03'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">@json($date)</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" 
                                  range />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '["2020-01-01","2020-01-03"]')
            ->assertSeeIn('@date', '["2020-01-01","2020-01-03"]')
            ->click('@tallstackui_datepicker_open_close')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[7]/button')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[14]/button')
            ->waitForTextIn('@date', '["2020-01-04","2020-01-11"]')
            ->assertSeeIn('@date', '["2020-01-04","2020-01-11"]');
    }

    /** @test */
    public function can_select_today(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_datepicker_open_close')
            ->waitForText('January')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/span/button[1]')
            ->waitForText(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])
            ->assertSee('Jan')
            ->assertSee('Mar')
            ->assertSee('Dec')
            ->assertSee('Today')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/div/div/div/button[2]')
            ->waitUntilMissingText('Today')
            ->assertDontSee('Today');
    }

    /** @test */
    public function can_use_today_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  helpers
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_datepicker_open_close')
            ->waitForText('Yesterday')
            ->click('@tallstackui_datepicker_helper_today')
            ->waitForTextIn('@date', $date = now()->format('Y-m-d'))
            ->assertSeeIn('@date', $date);
    }

    /** @test */
    public function can_use_tomorrow_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  helpers
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_datepicker_open_close')
            ->waitForText('Yesterday')
            ->click('@tallstackui_datepicker_helper_tomorrow')
            ->waitForTextIn('@date', $date = now()->addDay()->format('Y-m-d'))
            ->assertSeeIn('@date', $date);
    }

    /** @test */
    public function can_use_yesterday_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>
                    
                    <x-datepicker label="DatePicker"
                                  helpers
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_datepicker_open_close')
            ->waitForText('Yesterday')
            ->click('@tallstackui_datepicker_helper_yesterday')
            ->waitForTextIn('@date', $date = now()->subDay()->format('Y-m-d'))
            ->assertSeeIn('@date', $date);
    }

    /** @test */
    public function cannot_use_a_min_date_greater_than_max_date(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">@json($date)</p>
                    
                    <x-datepicker label="DatePicker"
                                  min-date="2024-01-01"
                                  max-date="2020-01-01"
                                  wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->assertSee('The datepicker [min-date] must be less than or equal to [max-date]');
    }
}
