<?php

namespace TallStackUi\Components\Accordion;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_toggle_on_click(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-accordion>
                        <x-accordion.items title="Question" id="item-1">
                            <p>The answer</p>
                        </x-accordion.items>
                    </x-accordion>
                </div>
                HTML;
            }
        })
            ->assertSee('Question')
            ->assertDontSee('The answer')
            ->click('@tallstackui_accordion_trigger_item-1')
            ->waitForText('The answer')
            ->assertSee('The answer')
            ->click('@tallstackui_accordion_trigger_item-1')
            ->waitUntilMissingText('The answer');
    }

    #[Test]
    public function dispatches_open_and_close_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $lastEvent = null;

            public ?string $lastId = null;

            public function capture(string $event, string $id): void
            {
                $this->lastEvent = $event;
                $this->lastId = $id;
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="last-event">{{ $lastEvent }}</p>
                    <p dusk="last-id">{{ $lastId }}</p>
                    <x-accordion
                        x-on:open="$wire.capture('open', $event.detail.id)"
                        x-on:close="$wire.capture('close', $event.detail.id)"
                    >
                        <x-accordion.items title="Q" id="evt-1"><p>A</p></x-accordion.items>
                    </x-accordion>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_accordion_trigger_evt-1')
            ->waitForTextIn('@last-event', 'open')
            ->assertSeeIn('@last-id', 'evt-1')
            ->click('@tallstackui_accordion_trigger_evt-1')
            ->waitForTextIn('@last-event', 'close');
    }

    #[Test]
    public function multiple_mode_keeps_siblings_open(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-accordion multiple>
                        <x-accordion.items title="First" id="m1"><p>BodyOne</p></x-accordion.items>
                        <x-accordion.items title="Second" id="m2"><p>BodyTwo</p></x-accordion.items>
                    </x-accordion>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_accordion_trigger_m1')
            ->waitForText('BodyOne')
            ->click('@tallstackui_accordion_trigger_m2')
            ->waitForText('BodyTwo')
            ->assertSee('BodyOne')
            ->assertSee('BodyTwo');
    }

    #[Test]
    public function single_open_mode_closes_siblings(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-accordion>
                        <x-accordion.items title="First" id="s1"><p>BodyOne</p></x-accordion.items>
                        <x-accordion.items title="Second" id="s2"><p>BodyTwo</p></x-accordion.items>
                    </x-accordion>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_accordion_trigger_s1')
            ->waitForText('BodyOne')
            ->click('@tallstackui_accordion_trigger_s2')
            ->waitForText('BodyTwo')
            ->waitUntilMissingText('BodyOne')
            ->assertDontSee('BodyOne');
    }
}
