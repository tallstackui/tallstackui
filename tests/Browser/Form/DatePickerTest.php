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
        //
    }

    /** @test */
    public function can_advance_to_next_year(): void
    {
        $this->markTestSkipped('Not implemented yet.');
    }

    /** @test */
    public function can_previous_to_last_month(): void
    {
        $this->markTestSkipped('Not implemented yet.');
    }

    /** @test */
    public function can_previous_to_last_year(): void
    {
        $this->markTestSkipped('Not implemented yet.');
    }

    /** @test */
    public function can_select_date() //OK
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
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[5]/button')
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
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[4]/button')
            ->waitForTextIn('@date', '["2020-01-03"]')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[5]/button')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[7]/button')
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
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[7]/button')
            ->clickAtXPath('/html/body/div[3]/div/div/div/div[2]/div[3]/div[14]/button')
            ->waitForTextIn('@date', '["2020-01-04","2020-01-11"]')
            ->assertSeeIn('@date', '["2020-01-04","2020-01-11"]');
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
