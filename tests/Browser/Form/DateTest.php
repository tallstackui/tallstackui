<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class DateTest extends BrowserTestCase
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

                    <x-date label="DatePicker" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->click('@tallstackui_date_previous_month')
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

                    <x-date label="DatePicker" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('2020')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/span/button[2]')
            ->waitForText('2009')
            ->assertSee('2009')
            ->click('@tallstackui_date_previous_year')
            ->waitForText('1990')
            ->assertSee('1990');
    }

    /** @test */
    public function can_boot_on_first_date(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '1999-06-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '1999-06-01')
            ->assertSeeIn('@date', '1999-06-01')
            ->click('@tallstackui_date_open_close')
            ->waitForText('June')
            ->assertSee('June')
            ->assertSee('1999');
    }

    /** @test */
    public function can_boot_on_first_date_in_multiple(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $date = ['1999-06-01', '2023-01-01', '2024-01-01'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">@json($date)</p>

                    <x-date label="DatePicker" wire:model.live="date" multiple />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '["1999-06-01","2023-01-01","2024-01-01"]')
            ->assertSeeIn('@date', '["1999-06-01","2023-01-01","2024-01-01"]')
            ->click('@tallstackui_date_open_close')
            ->waitForText('June')
            ->assertSee('June')
            ->assertSee('1999');
    }

    /** @test */
    public function can_boot_on_first_date_in_range(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $date = ['1999-06-01', '2024-01-01'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">@json($date)</p>

                    <x-date label="DatePicker" wire:model.live="date" range />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '["1999-06-01","2024-01-01"]')
            ->assertSeeIn('@date', '["1999-06-01","2024-01-01"]')
            ->click('@tallstackui_date_open_close')
            ->waitForText('June')
            ->assertSee('June')
            ->assertSee('1999');
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

                    <x-date label="DatePicker" x-on:clear="$wire.set('selected', 1)" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '2020-01-01')
            ->assertSeeIn('@date', '2020-01-01')
            ->click('@tallstackui_date_clear')
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

            public ?bool $selected = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    @if ($selected)
                        <p dusk="selected">{{ $selected }}</p>
                    @endif

                    <x-date label="DatePicker" x-on:select="$wire.set('selected', 1)" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '2020-01-01')
            ->assertSeeIn('@date', '2020-01-01')
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[5]/button')
            ->waitForTextIn('@selected', '1')
            ->assertSeeIn('@selected', '1');
    }

    /** @test */
    public function can_navigate_to_last_year(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('2020')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/span/button[2]')
            ->waitForText('2009')
            ->assertSee('2009')
            ->click('@tallstackui_date_next_year')
            ->waitForText('2040')
            ->assertSee('2040');
    }

    /** @test */
    public function can_navigate_to_previous_month(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->click('@tallstackui_date_next_month')
            ->waitForText('February')
            ->assertSee('February');
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

                    <x-date label="DatePicker" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/span/button[1]')
            ->waitForText(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])
            ->assertSee('Jan')
            ->assertSee('Mar')
            ->assertSee('Dec');
    }

    /** @test */
    public function can_react_to_wire_change(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $changed = false;

            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($changed)
                        <p dusk="changed">Changed</p>
                    @endif
                
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:change="change" />
                </div>
                HTML;
            }

            public function change(): void
            {
                $this->changed = true;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText(now()->monthName)
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[18]/button')
            ->waitForTextIn('@changed', 'Changed');
    }

    /** @test */
    public function can_react_to_wire_change_through_helpers(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $changed = false;

            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($changed)
                        <p dusk="changed">Changed</p>
                    @endif

                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" helpers wire:change="change" />
                </div>
                HTML;
            }

            public function change(): void
            {
                $this->changed = true;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('Yesterday')
            ->click('@tallstackui_date_helper_today')
            ->waitForTextIn('@changed', 'Changed');
    }

    /** @test */
    public function can_reset_calendar_when_reopen(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/div/button[2]')
            ->pause(50)
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/div/button[2]')
            ->pause(50)
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/div/button[2]')
            ->pause(50)
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/div/button[2]')
            ->pause(50)
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/div/button[2]')
            ->pause(50)
            ->waitForText('June')
            ->assertSee('June')
            ->click('@tallstackui_date_open_close')
            ->pause(100)
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->assertSee('January')
            ->assertDontSee('June');
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

                    <x-date label="DatePicker" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '2020-01-01')
            ->assertSeeIn('@date', '2020-01-01')
            ->click('@tallstackui_date_open_close')
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

                    <x-date label="DatePicker" wire:model.live="date" multiple />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '["2020-01-01","2020-01-03"]')
            ->assertSeeIn('@date', '["2020-01-01","2020-01-03"]')
            ->click('@tallstackui_date_open_close')
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

                    <x-date label="DatePicker" wire:model.live="date" range />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '["2020-01-01","2020-01-03"]')
            ->assertSeeIn('@date', '["2020-01-01","2020-01-03"]')
            ->click('@tallstackui_date_open_close')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[7]/button')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[14]/button')
            ->waitForTextIn('@date', '["2020-01-04","2020-01-11"]')
            ->assertSeeIn('@date', '["2020-01-04","2020-01-11"]');
    }

    /** @test */
    public function can_select_month_year()
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" month-year-only />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '2020-01')
            ->assertSeeIn('@date', '2020-01')
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->assertDontSee('Sun')
            ->assertDontSee('Mon')
            ->assertDontSee('Tue')
            ->assertDontSee('Wed')
            ->assertDontSee('Thu')
            ->assertDontSee('Fri')
            ->assertDontSee('Sat')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/div[1]/div/button[2]')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[1]/div[1]/div/button[13]')
            ->waitForTextIn('@date', '2021-02')
            ->assertSeeIn('@date', '2021-02');
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

                    <x-date label="DatePicker" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
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
    public function can_use_max_date(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2024-01-02';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" max-date="2024-01-02" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '2024-01-02')
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[4]/button')
            ->waitForTextIn('@date', '2024-01-02')
            ->assertSeeIn('@date', '2024-01-02')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[2]/button')
            ->waitForTextIn('@date', '2024-01-01')
            ->assertSeeIn('@date', '2024-01-01');
    }

    /** @test */
    public function can_use_min_date(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2024-01-03';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" min-date="2024-01-02" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '2024-01-03')
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[3]/button')
            ->waitForTextIn('@date', '2024-01-02')
            ->assertSeeIn('@date', '2024-01-02')
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div[3]/div[5]/button')
            ->waitForTextIn('@date', '2024-01-04')
            ->assertSeeIn('@date', '2024-01-04');
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

                    <x-date label="DatePicker" helpers wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('Yesterday')
            ->click('@tallstackui_date_helper_today')
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

                    <x-date label="DatePicker" helpers wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForText('DatePicker')
            ->click('@tallstackui_date_open_close')
            ->waitForText('Yesterday')
            ->click('@tallstackui_date_helper_tomorrow')
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

                    <x-date label="DatePicker" helpers wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('Yesterday')
            ->click('@tallstackui_date_helper_yesterday')
            ->waitForTextIn('@date', $date = now()->subDays()->format('Y-m-d'))
            ->assertSeeIn('@date', $date);
    }

    /** @test */
    public function cannot_navigate_beyond_max_year(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2024-12-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" :max-year="2024" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('December')
            // Double check
            ->click('@tallstackui_date_next_month')
            ->waitForText('December')
            ->assertSee('December')
            ->assertDontSee('January')
            ->click('@tallstackui_date_next_month')
            ->waitForText('December')
            ->assertSee('December')
            ->assertDontSee('January');
    }

    /** @test */
    public function cannot_navigate_beyond_min_year(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2024-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" :min-year="2024" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            // Double check
            ->click('@tallstackui_date_previous_month')
            ->waitForText('January')
            ->assertSee('January')
            ->assertDontSee('December')
            ->click('@tallstackui_date_previous_month')
            ->waitForText('January')
            ->assertSee('January')
            ->assertDontSee('December');
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

                    <x-date label="DatePicker" min-date="2024-01-01" max-date="2020-01-01" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->assertSee('The date [min-date] must be less than or equal to [max-date].');
    }

    /** @test */
    public function cannot_use_a_min_year_greater_than_max_year(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" min-year="2024" max-year="2020" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->assertSee('The year [min-year] must be less than or equal to [max-year].');
    }

    /** @test */
    public function cannot_use_the_first_date_greater_than_the_second_on_range(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $date = ['2020-01-01', '2019-01-01'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">@json($date)</p>

                    <x-date label="DatePicker" wire:model.live="date" range />
                </div>
                HTML;
            }
        })
            ->assertSee('The start date in the [range] must be greater than the second date.');
    }
}
