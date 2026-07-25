<?php

namespace TallStackUi\Components\Calendar;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_navigate_to_next_month(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2026-04-15';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('April')
            ->click('@tallstackui_date_next_month')
            ->waitForText('May')
            ->assertSee('May');
    }

    #[Test]
    public function can_navigate_to_previous_month(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2026-04-15';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('April')
            ->click('@tallstackui_date_previous_month')
            ->waitForText('March')
            ->assertSee('March');
    }

    #[Test]
    public function can_open_month_picker_and_select_a_month(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2026-04-15';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('April')
            ->clickAtVisibleXPath('(//button[normalize-space(text())="April"])[1]')
            ->waitForText('Aug')
            ->clickAtVisibleXPath('(//button[normalize-space(text())="Aug"])[1]')
            ->pause(300)
            ->waitForText('August')
            ->assertSee('August');
    }

    #[Test]
    public function can_open_year_picker_and_select_a_year(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2026-04-15';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('2026')
            ->clickAtVisibleXPath('(//button[normalize-space(text())="2026"])[1]')
            ->waitForText('2034')
            ->clickAtVisibleXPath('(//button[normalize-space(text())="2020"])[1]')
            ->pause(300)
            ->waitForText('2020')
            ->assertSee('2020');
    }

    #[Test]
    public function can_select_a_day(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date ?? '(none)' }}</p>
                    <x-calendar wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Sun')
            ->clickAtVisibleXPath('(//button[contains(@class, "rounded-full") and not(@disabled)])[8]')
            ->pause(500)
            ->waitUntilMissingText('(none)')
            ->assertDontSeeIn('@date', '(none)');
    }

    #[Test]
    public function can_select_a_range_across_two_clicks(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $date = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar range wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Sun')
            ->clickAtVisibleXPath('(//button[contains(@class, "rounded-full") and not(@disabled)])[5]')
            ->pause(300)
            ->clickAtVisibleXPath('(//button[contains(@class, "rounded-full") and not(@disabled)])[15]')
            ->pause(500)
            ->assertPresent('[class*="bg-primary-500"]');
    }

    #[Test]
    public function double_mode_can_select_end_date_from_secondary_calendar(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $date = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar range double wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Sun')
            ->clickAtVisibleXPath('(//button[contains(@class, "rounded-full") and not(@disabled)])[5]')
            ->pause(300)
            ->clickAtVisibleXPath('(//button[contains(@class, "rounded-full") and not(@disabled)])[45]')
            ->pause(500)
            ->assertPresent('[class*="bg-primary-500"]');
    }

    #[Test]
    public function double_mode_primary_navigation_advances_secondary_calendar(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $date = ['2026-04-15'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar range double wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('April')
            ->assertSee('May')
            ->assertDontSee('June')
            ->click('@tallstackui_date_next_month')
            ->waitForText('June')
            ->assertSee('May')
            ->assertSee('June');
    }

    #[Test]
    public function helpers_today_button_is_rendered(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar helpers wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Sun')
            ->assertPresent('[dusk="tallstackui_date_helper_today"]');
    }

    #[Test]
    public function helpers_today_button_selects_today(): void
    {
        $today = now()->format('Y-m-d');

        Livewire::visit(new class extends Component
        {
            public ?string $date = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="date">{{ $date ?? '(none)' }}</p>
                    <x-calendar helpers wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Sun')
            ->click('@tallstackui_date_helper_today')
            ->waitForTextIn('@date', $today)
            ->assertSeeIn('@date', $today);
    }

    #[Test]
    public function lock_month_year_renders_locked_styles_on_header_buttons(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2026-04-15';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar lock-month-year wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('April')
            ->assertPresent('[x-ref="monthAnchor"].pointer-events-none')
            ->assertPresent('[x-ref="yearAnchor"].pointer-events-none')
            ->assertDontSee('Jan')
            ->assertDontSee('2034');
    }

    #[Test]
    public function renders_double_mode_with_two_month_headers(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $date = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar range double wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Sun')
            ->pause(300)
            ->assertPresent('[dusk="tallstackui_calendar"]');
    }

    #[Test]
    public function renders_inline_with_month_header(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $date = '2026-04-15';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-calendar wire:model.live="date" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('April')
            ->assertSee('April')
            ->assertSee('2026');
    }
}
