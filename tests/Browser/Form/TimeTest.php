<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class TimeTest extends BrowserTestCase
{
    /** @test */
    public function can_change_interval()
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
            ->click('@tallstackui_timepicker_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_timepicker_hours', 5)
            ->waitForTextIn('@time', '07:00 AM')
            ->assertSeeIn('@time', '07:00 AM')
            ->click('@tallstackui_timepicker_pm')
            ->waitForTextIn('@time', '07:00 PM')
            ->assertSeeIn('@time', '07:00 PM')
            ->click('@tallstackui_timepicker_am')
            ->waitForTextIn('@time', '07:00 AM')
            ->assertSeeIn('@time', '07:00 AM');
    }

    /** @test */
    public function can_dispatch_select_hour_event()
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
            ->click('@tallstackui_timepicker_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_timepicker_hours', 5)
            ->waitForTextIn('@time', '7')
            ->assertSeeIn('@time', '7');
    }

    /** @test */
    public function can_dispatch_select_minute_event()
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
            ->click('@tallstackui_timepicker_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_timepicker_minutes', 5)
            ->waitForTextIn('@time', '31')
            ->assertSeeIn('@time', '31');
    }

    /** @test */
    public function can_render_footer_slot()
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
            ->click('@tallstackui_timepicker_input')
            ->waitForText('FooBarBaz')
            ->assertSee('FooBarBaz');
    }

    /** @test */
    public function can_select_current_hour()
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
                                  wire:model.live="time" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_timepicker_input')
            ->waitForText('00')
            ->waitForLivewire()->click('@tallstackui_timepicker_current')
            ->pause(100)
            ->assertVisible('@time');
    }

    /** @test */
    public function can_select_hour()
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
            ->click('@tallstackui_timepicker_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_timepicker_hours', 5)
            ->waitForTextIn('@time', '7')
            ->assertSeeIn('@time', '7');
    }

    /** @test */
    public function can_select_minute()
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
            ->click('@tallstackui_timepicker_input')
            ->waitForText('00')
            ->dragRight('@tallstackui_timepicker_minutes', 5)
            ->waitForTextIn('@time', '31')
            ->assertSeeIn('@time', '31');
    }
}
