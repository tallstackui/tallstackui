<?php

namespace TallStackUi\Components\Form\Time;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_change_interval(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = '12:00 AM';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="time">{{ $time }}</p>
                    
                    <x-time label="Time"
                                  wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_time_hours', 5)
            ->waitForTextIn('@time', '07:00 AM')
            ->assertSeeIn('@time', '07:00 AM')
            ->click('@tallstackui_time_pm')
            ->waitForTextIn('@time', '07:00 PM')
            ->assertSeeIn('@time', '07:00 PM')
            ->click('@tallstackui_time_input')
            ->waitForText('07')
            ->assertSee('07')
            ->click('@tallstackui_time_am')
            ->waitForTextIn('@time', '07:00 AM')
            ->assertSeeIn('@time', '07:00 AM');
    }

    #[Test]
    public function can_clear(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = '11:30 PM';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($time)
                        <p dusk="time">{{ $time }}</p>
                    @endif
                    
                    <x-time label="Time"
                            wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Time')
            ->assertSee('Time')
            ->click('@tallstackui_time_clear')
            ->waitUntilMissing('@time');
    }

    #[Test]
    public function can_dispatch_select_hour_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($time)
                        <p dusk="time">{{ $time }}</p>
                    @endif
                    
                    <x-time label="Time"
                                  helper
                                  x-on:hour="$wire.set('time', $event.detail.hour)" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_time_hours', 5)
            ->waitForTextIn('@time', '7')
            ->assertSeeIn('@time', '7');
    }

    #[Test]
    public function can_dispatch_select_minute_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($time)
                        <p dusk="time">{{ $time }}</p>
                    @endif
                    
                    <x-time label="Time"
                                  helper
                                  x-on:minute="$wire.set('time', $event.detail.minute)" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_time_minutes', 5)
            ->waitForTextIn('@time', '31')
            ->assertSeeIn('@time', '31');
    }

    #[Test]
    public function can_dispatch_select_minute_interval(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $interval = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($interval)
                        <p dusk="interval">{{ $interval }}</p>
                    @endif
                    
                    <x-time label="Time" x-on:interval="$wire.set('interval', $event.detail.interval)" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->click('@tallstackui_time_pm')
            ->waitForTextIn('@interval', 'PM')
            ->assertSeeIn('@interval', 'PM')
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->click('@tallstackui_time_am')
            ->waitForTextIn('@interval', 'AM')
            ->assertSeeIn('@interval', 'AM');
    }

    #[Test]
    public function can_render_footer_slot(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-time label="Time">
                        <x-slot:footer>
                            FooBarBaz
                        </x-slot:footer>
                    </x-time>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('FooBarBaz')
            ->assertSee('FooBarBaz');
    }

    #[Test]
    public function can_see_the_clear_button_when_not_required(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = '11:30 PM';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-time label="Time"
                            wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Time')
            ->assertSee('Time')
            ->assertPresent('@tallstackui_time_clear');
    }

    #[Test]
    public function can_select_current_hour(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($time)
                        <p dusk="time">{{ $time }}</p>
                    @endif
                    
                    <x-time label="Time" format="24" helper wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->waitForLivewire()->click('@tallstackui_time_current')
            ->pause(100)
            ->assertVisible('@time');
    }

    #[Test]
    public function can_select_hour(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="time">{{ $time }}</p>
                    
                    <x-time label="Time" wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_time_hours', 5)
            ->waitForTextIn('@time', '7')
            ->assertSeeIn('@time', '7');
    }

    #[Test]
    public function can_select_minute(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="time">{{ $time }}</p>
                    
                    <x-time label="Time" wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_time_minutes', 5)
            ->waitForTextIn('@time', '31')
            ->assertSeeIn('@time', '31');
    }

    #[Test]
    public function cannot_pass_the_max_hour(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="time">{{ $time }}</p>
                    
                    <x-time label="Time"
                            :max-hour="5" 
                            wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_time_hours', 30)
            ->waitForTextIn('@time', '05:00 AM')
            ->assertSeeIn('@time', '05:00 AM');
    }

    #[Test]
    public function cannot_pass_the_max_minute(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = '11:00 PM';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="time">{{ $time }}</p>
                    
                    <x-time label="Time"
                            :max-minute="30" 
                            wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('11')
            ->waitForText('00')
            ->waitForText('PM')
            ->dragRight('@tallstackui_time_minutes', 50)
            ->waitForTextIn('@time', '11:30 PM')
            ->assertSeeIn('@time', '11:30 PM');
    }

    #[Test]
    public function cannot_pass_the_min_hour(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = '11:30 PM';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="time">{{ $time }}</p>
                    
                    <x-time label="Time"
                            :min-hour="5" 
                            wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('11')
            ->waitForText('30')
            ->waitForText('PM')
            ->dragLeft('@tallstackui_time_hours', 30)
            ->waitForTextIn('@time', '05:30 PM')
            ->assertSeeIn('@time', '05:30 PM');
    }

    #[Test]
    public function cannot_pass_the_min_minute(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = '11:30 PM';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="time">{{ $time }}</p>
                    
                    <x-time label="Time"
                            :min-minute="15" 
                            wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_time_input')
            ->waitForText('11')
            ->waitForText('30')
            ->waitForText('PM')
            ->dragLeft('@tallstackui_time_minutes', 200)
            ->waitForTextIn('@time', '11:15 PM')
            ->assertSeeIn('@time', '11:15 PM');
    }

    #[Test]
    public function cannot_see_the_clear_button_when_required(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = '11:30 PM';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-time label="Time"
                            required
                            wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Time')
            ->assertSee('Time')
            ->assertNotPresent('@tallstackui_time_clear');
    }

    #[Test]
    public function cannot_use_12_hour_format_without_interval(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = '11:30';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-time label="Time" wire:model.live="time" />
                </div>
                HTML;
            }
        })->assertSee('[TallStackUI] Form\Time: The [format] is not 24 and the value does not contain the interval (AM/PM).');
    }

    #[Test]
    public function current_time_helper_keeps_the_twenty_four_hour_reading(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="time">{{ $time }}</p>

                    <x-time label="Time" format="24" helper wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->tap(fn (Browser $browser) => $this->freeze($browser, 13, 45))
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->waitForLivewire()->click('@tallstackui_time_current')
            ->pause(500)
            ->assertSeeIn('@time', '13:45');
    }

    #[Test]
    public function current_time_helper_stays_within_the_twelve_hour_range(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="time">{{ $time }}</p>

                    <x-time label="Time" format="12" helper wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->tap(fn (Browser $browser) => $this->freeze($browser, 13, 45))
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->waitForLivewire()->click('@tallstackui_time_current')
            ->pause(500)
            ->tap(function (Browser $browser): void {
                // 13:45 off a 24-hour clock used to be written as "13:45 PM",
                // outside the 1-12 range the slider accepts.
                $browser->assertSeeIn('@time', '01:45 PM');
            });
    }

    #[Test]
    public function current_time_helper_writes_midnight_as_twelve_am(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $time = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="time">{{ $time }}</p>

                    <x-time label="Time" format="12" helper wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->tap(fn (Browser $browser) => $this->freeze($browser, 0, 30))
            ->click('@tallstackui_time_input')
            ->waitForText('00')
            ->waitForLivewire()->click('@tallstackui_time_current')
            ->pause(500)
            // Hour 0 used to be written as "00:30 AM" instead of "12:30 AM".
            ->assertSeeIn('@time', '12:30 AM');
    }

    /**
     * Pins the browser clock so the helper's reading does not depend on the
     * hour the suite happens to run at.
     */
    private function freeze(Browser $browser, int $hour, int $minute): void
    {
        $browser->script(
            "(() => {
                const Original = Date;
                const frozen = () => new Original(2026, 0, 15, {$hour}, {$minute}, 0);

                window.Date = class extends Original {
                    constructor(...args) {
                        super(...(args.length === 0 ? [frozen().getTime()] : args));
                    }

                    static now() {
                        return frozen().getTime();
                    }
                };
            })();"
        );
    }
}
