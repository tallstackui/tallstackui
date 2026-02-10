<?php

namespace TallStackUi\Components\Breadcrumbs;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_render_all_size_variants(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <div dusk="xs">
                        <x-breadcrumbs xs :items="[['label' => 'A', 'link' => '/'], ['label' => 'B']]" />
                    </div>
                    <div dusk="sm">
                        <x-breadcrumbs sm :items="[['label' => 'A', 'link' => '/'], ['label' => 'B']]" />
                    </div>
                    <div dusk="md">
                        <x-breadcrumbs :items="[['label' => 'A', 'link' => '/'], ['label' => 'B']]" />
                    </div>
                    <div dusk="lg">
                        <x-breadcrumbs lg :items="[['label' => 'A', 'link' => '/'], ['label' => 'B']]" />
                    </div>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertPresent('@xs')
            ->assertPresent('@sm')
            ->assertPresent('@md')
            ->assertPresent('@lg');
    }

    #[Test]
    public function can_render_current_item_as_non_clickable(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-breadcrumbs :items="[
                        ['label' => 'Home', 'link' => '/'],
                        ['label' => 'Current Page'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSourceHas('font-medium')
            ->assertSee('Current Page');
    }

    #[Test]
    public function can_render_items_and_navigate(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-breadcrumbs :items="[
                        ['label' => 'Home', 'link' => '/'],
                        ['label' => 'Users', 'link' => '/users'],
                        ['label' => 'John Doe'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('Home')
            ->assertSee('Users')
            ->assertSee('John Doe');
    }

    #[Test]
    public function can_render_slots(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-breadcrumbs :items="[['label' => 'Home', 'link' => '/']]">
                        <x-slot:left>
                            <span dusk="left-slot">Left</span>
                        </x-slot:left>
                        <x-slot:right>
                            <span dusk="right-slot">Right</span>
                        </x-slot:right>
                    </x-breadcrumbs>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertVisible('@left-slot')
            ->assertVisible('@right-slot');
    }

    #[Test]
    public function can_render_tooltip_on_hover(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-breadcrumbs :items="[
                        ['label' => 'Home', 'link' => '/', 'tooltip' => 'Back to homepage'],
                        ['label' => 'Page'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSourceHas('x-tooltip="Back to homepage"');
    }

    #[Test]
    public function can_render_with_icon_separator(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-breadcrumbs separator="icon:chevron-right" :items="[
                        ['label' => 'Home', 'link' => '/'],
                        ['label' => 'Page'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('Home')
            ->assertSee('Page')
            ->assertSourceHas('<svg');
    }

    #[Test]
    public function can_render_with_livewire_dynamic_items(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $items = [
                ['label' => 'Home', 'link' => '/'],
            ];

            public function addItem(): void
            {
                $this->items[] = ['label' => 'New Page'];
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-breadcrumbs :items="$items" />
                    <button wire:click="addItem" dusk="add-item">Add</button>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('Home')
            ->click('@add-item')
            ->waitForText('New Page')
            ->assertSee('New Page');
    }
}
