<?php

namespace TallStackUi\Components\Timeline;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_render_horizontal(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-timeline horizontal>
                        <x-timeline.items title="Step A" />
                        <x-timeline.items title="Step B" />
                    </x-timeline>
                </div>
                HTML;
            }
        })
            ->assertSee('Step A')
            ->assertSee('Step B');
    }

    #[Test]
    public function can_render_vertical(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-timeline>
                        <x-timeline.items title="v1.0" description="First release" date="Jan 2026" />
                        <x-timeline.items title="v2.0" description="Second release" date="Feb 2026" />
                    </x-timeline>
                </div>
                HTML;
            }
        })
            ->assertSee('v1.0')
            ->assertSee('v2.0')
            ->assertSee('First release');
    }

    #[Test]
    public function can_render_with_custom_marker_slot(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-timeline>
                        <x-timeline.items title="Custom">
                            <x-slot:marker>
                                <span data-dusk="custom-marker" class="text-xs font-bold">99</span>
                            </x-slot:marker>
                        </x-timeline.items>
                    </x-timeline>
                </div>
                HTML;
            }
        })
            ->assertSee('99')
            ->assertSee('Custom');
    }

    #[Test]
    public function can_render_with_icon_marker(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-timeline>
                        <x-timeline.items title="Rocket" icon="rocket-launch" />
                    </x-timeline>
                </div>
                HTML;
            }
        })
            ->assertSee('Rocket')
            ->assertPresent('svg');
    }

    #[Test]
    public function throws_when_items_and_slot_are_provided_simultaneously(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $items = [['title' => 'Array item']];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-timeline :items="$items">
                        <x-timeline.items title="Slot item" />
                    </x-timeline>
                </div>
                HTML;
            }
        })
            ->assertSee('[TallStackUI] Timeline')
            ->assertSee('Cannot pass both [:items] and slot content simultaneously');
    }

    #[Test]
    public function throws_when_style_is_invalid(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-timeline style="wild">
                        <x-timeline.items title="A" />
                    </x-timeline>
                </div>
                HTML;
            }
        })
            ->assertSee('[TallStackUI] Timeline')
            ->assertSee('[style] prop must be one of: solid, light, outline');
    }
}
