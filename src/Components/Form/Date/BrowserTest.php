<?php

namespace TallStackUi\Components\Form\Date;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
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

    #[Test]
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/span/button[2]')
            ->waitForText('2009')
            ->assertSee('2009')
            ->click('@tallstackui_date_previous_year')
            ->waitForText('1990')
            ->assertSee('1990');
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[5]/button')
            ->waitForTextIn('@selected', '1')
            ->assertSeeIn('@selected', '1');
    }

    #[Test]
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/span/button[2]')
            ->waitForText('2009')
            ->assertSee('2009')
            ->click('@tallstackui_date_next_year')
            ->waitForText('2040')
            ->assertSee('2040');
    }

    #[Test]
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

    #[Test]
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/span/button[1]')
            ->waitForAllText(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])
            ->assertSee('Jan')
            ->assertSee('Mar')
            ->assertSee('Dec');
    }

    #[Test]
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[18]/button')
            ->waitForTextIn('@changed', 'Changed');
    }

    #[Test]
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

    #[Test]
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/div/button[2]')
            ->pause(50)
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/div/button[2]')
            ->pause(50)
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/div/button[2]')
            ->pause(50)
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/div/button[2]')
            ->pause(50)
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/div/button[2]')
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

    #[Test]
    public function can_select_date(): void
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[5]/button')
            ->waitForTextIn('@date', '2020-01-02')
            ->assertSeeIn('@date', '2020-01-02');
    }

    #[Test]
    public function can_select_date_on_multiple(): void
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[4]/button')
            ->waitForTextIn('@date', '["2020-01-03"]')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[5]/button')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[7]/button')
            ->waitForTextIn('@date', '["2020-01-03","2020-01-02","2020-01-04"]')
            ->assertSeeIn('@date', '["2020-01-03","2020-01-02","2020-01-04"]');
    }

    #[Test]
    public function can_select_date_on_range(): void
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[7]/button')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[14]/button')
            ->waitForTextIn('@date', '["2020-01-04","2020-01-11"]')
            ->assertSeeIn('@date', '["2020-01-04","2020-01-11"]');
    }

    #[Test]
    public function can_select_month_year(): void
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/div[1]/div/button[2]')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/div[1]/div/button[13]')
            ->waitForTextIn('@date', '2021-02')
            ->assertSeeIn('@date', '2021-02');
    }

    #[Test]
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/span/button[1]')
            ->waitForAllText(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])
            ->assertSee('Jan')
            ->assertSee('Mar')
            ->assertSee('Dec')
            ->assertSee('Today')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[1]/div/div/div/button[2]')
            ->waitUntilMissingText('Today')
            ->assertDontSee('Today');
    }

    #[Test]
    public function can_start_the_week_on_a_custom_day(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" start="1" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->tap(function (Browser $browser): void {
                $header = $browser->driver->findElement(
                    WebDriverBy::xpath('(//div[@data-floating])[1]/div[2]/div[1]')
                );

                $this->assertSame('Mon', trim($header->getText()));
            })
            // January 2020 opens on a Wednesday, which leaves two blanks ahead of
            // the 1st once the week starts on Monday instead of three.
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[12]/button')
            ->waitForTextIn('@date', '2020-01-10')
            ->assertSeeIn('@date', '2020-01-10');
    }

    #[Test]
    public function can_use_custom_formats(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-15';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-date label="Default" wire:model.live="date" dusk="date_default" />
                    <x-date label="Slashed" wire:model.live="date" dusk="date_slashed" format="DD/MM/YYYY" />
                    <x-date label="Long" wire:model.live="date" dusk="date_long" format="YYYY, MMMM, DD" />
                    <x-date label="Escaped" wire:model.live="date" dusk="date_escaped" format="DD [of] MMMM [of] YYYY" />
                    <x-date label="Weekday" wire:model.live="date" dusk="date_weekday" format="dddd, MMM D" start="1" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Weekday')
            ->assertInputValue('@date_default', '2020-01-15')
            ->assertInputValue('@date_slashed', '15/01/2020')
            ->assertInputValue('@date_long', '2020, January, 15')
            ->assertInputValue('@date_escaped', '15 of January of 2020')
            // The weekday name is read from the date itself, so the rotation
            // `start` applies to the header must never reach it.
            ->assertInputValue('@date_weekday', 'Wednesday, Jan 15');
    }

    #[Test]
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[4]/button')
            ->waitForTextIn('@date', '2024-01-02')
            ->assertSeeIn('@date', '2024-01-02')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[2]/button')
            ->waitForTextIn('@date', '2024-01-01')
            ->assertSeeIn('@date', '2024-01-01');
    }

    #[Test]
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
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[3]/button')
            ->waitForTextIn('@date', '2024-01-02')
            ->assertSeeIn('@date', '2024-01-02')
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[5]/button')
            ->waitForTextIn('@date', '2024-01-04')
            ->assertSeeIn('@date', '2024-01-04');
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function cannot_open_when_date_is_disabled(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" disabled />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitUntilMissingText('January')
            ->assertDontSee('January');
    }

    #[Test]
    public function cannot_open_when_date_is_readonly(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" readonly />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_date_open_close')
            ->waitUntilMissingText('January')
            ->assertDontSee('January');
    }

    #[Test]
    public function cannot_select_disabled_dates(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" :disable="['2020-01-10']" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForTextIn('@date', '2020-01-01')
            ->click('@tallstackui_date_open_close')
            ->waitForText('January')
            // January 2020 opens on a Wednesday, so three blanks precede the 1st.
            ->tap(function (Browser $browser): void {
                $day = $browser->driver->findElement(
                    WebDriverBy::xpath('(//div[@data-floating])[1]/div[3]/div[13]/button')
                );

                $this->assertSame('10', trim($day->getText()));
                $this->assertFalse($day->isEnabled());
            })
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[13]/button')
            ->assertSeeIn('@date', '2020-01-01')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[14]/button')
            ->waitForTextIn('@date', '2020-01-11')
            ->assertSeeIn('@date', '2020-01-11');
    }

    #[Test]
    public function cannot_select_other_days_different_than_only(): void
    {
        $this->travelTo(now()->createFromTimeString('2025-05-20 12:50:00'));

        Livewire::visit(new class extends Component
        {
            public ?string $date = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" only="1" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('DatePicker')
            ->assertSee('DatePicker')
            ->click('@tallstackui_date_open_close')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[24]/button')
            ->assertDontSeeIn('@date', '2025-05-20');
    }

    #[Test]
    public function cannot_select_weekdays_when_weekeend_is_enabled(): void
    {
        $this->travelTo(now()->createFromTimeString('2025-05-19 12:50:00'));

        $browser = Livewire::visit(new class extends Component
        {
            public ?string $date = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" weekends />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('DatePicker')
            ->assertSee('DatePicker')
            ->click('@tallstackui_date_open_close')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[23]/button')
            ->assertDontSeeIn('@date', '2025-05-19');

        $element = $browser->driver->findElement(WebDriverBy::xpath('(//div[@data-floating])[1]/div[3]/div[23]/button'));

        $this->assertStringContainsString('true', $element->getAttribute('disabled'));
    }

    #[Test]
    public function cannot_select_weekends_when_weekdays_is_enabled(): void
    {
        $this->travelTo(now()->createFromTimeString('2025-05-18 12:50:00'));

        $browser = Livewire::visit(new class extends Component
        {
            public ?string $date = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date }}</p>

                    <x-date label="DatePicker" wire:model.live="date" weekdays />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('DatePicker')
            ->assertSee('DatePicker')
            ->click('@tallstackui_date_open_close')
            ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[22]/button')
            ->assertDontSeeIn('@date', '2025-05-18');

        $element = $browser->driver->findElement(WebDriverBy::xpath('(//div[@data-floating])[1]/div[3]/div[22]/button'));

        $this->assertStringContainsString('true', $element->getAttribute('disabled'));
    }

    #[Test]
    public function cannot_set_starts_out_of_range(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $date = ['2020-01-01', '2019-01-01'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">@json($date)</p>

                    <x-date label="DatePicker" wire:model.live="date" range start="7" />
                </div>
                HTML;
            }
        })
            ->assertSee('[TallStackUI] Form\Date: The [start] attribute must be between 0 and 6.');
    }

    #[Test]
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
            ->assertSee('[TallStackUI] Form\Date: The [min-date] must be less than or equal to [max-date].');
    }

    #[Test]
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
            ->assertSee('[TallStackUI] Form\Date: The year [min-year] must be less than or equal to [max-year].');
    }

    #[Test]
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
            ->assertSee('[TallStackUI] Form\Date: The start date in the [range] must be greater than the second date.');
    }

    #[Test]
    public function clears_input_when_model_is_reset_to_null(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2020-01-01';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date ?? 'empty' }}</p>

                    <button type="button" dusk="reset" wire:click="$set('date', null)">Reset</button>

                    <x-date label="DatePicker" wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertInputValue('@tallstackui_date_input', '2020-01-01')
            ->click('@reset')
            ->waitForTextIn('@date', 'empty')
            ->assertInputValue('@tallstackui_date_input', '');
    }
}
